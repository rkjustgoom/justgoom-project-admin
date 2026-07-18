<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $this->resolvePerPage($request);

        $projects = $user->projects()
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => $user->projects()->count(),
            'documents' => $user->projects()->where('type', 'document')->count(),
            'videos' => $user->projects()->where('type', 'video')->count(),
            'links' => $user->projects()->where('type', 'link')->count(),
        ];

        return view('front.users.projects', compact('projects', 'stats'));
    }

    public function create()
    {
        return view('front.users.project-add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'type' => 'required|in:document,video,link',
            'file' => 'required_unless:type,link|nullable|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,mp4,avi,mov,wmv',
            'external_url' => 'required_if:type,link|nullable|url|max:500',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $project = new Project();
        $project->user_id = $request->user()->id;
        $project->title = $validated['title'];
        $project->description = $validated['description'] ?? null;
        $project->type = $validated['type'];
        $project->status = 1;

        if ($request->hasFile('file')) {
            $project->file_path = $this->uploadFile($request->file('file'), 'projects');
        }

        if (!empty($validated['external_url'])) {
            $project->external_url = $validated['external_url'];
        }

        if ($request->hasFile('thumbnail')) {
            $project->thumbnail = $this->uploadFile($request->file('thumbnail'), 'projects/thumbnails');
        }

        $project->save();

        return redirect()->route('front.users.projects')
            ->with('success', 'Project added successfully.');
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);

        return view('front.users.project-edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'type' => 'required|in:document,video,link',
            'file' => 'nullable|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,mp4,avi,mov,wmv',
            'external_url' => 'nullable|url|max:500',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $project->title = $validated['title'];
        $project->description = $validated['description'] ?? null;
        $project->type = $validated['type'];

        if ($request->hasFile('file')) {
            $this->deleteFile($project->file_path);
            $project->file_path = $this->uploadFile($request->file('file'), 'projects');
        }

        if (!empty($validated['external_url'])) {
            $project->external_url = $validated['external_url'];
        }

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($project->thumbnail);
            $project->thumbnail = $this->uploadFile($request->file('thumbnail'), 'projects/thumbnails');
        }

        $project->save();

        return redirect()->route('front.users.projects')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeProject($project);

        $this->deleteFile($project->file_path);
        $this->deleteFile($project->thumbnail);

        $project->delete();

        return redirect()->route('front.users.projects')
            ->with('success', 'Project deleted successfully.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        $project->update(['status' => (int) $validated['status']]);

        return back()->with('success', 'Project status updated.');
    }

    private function authorizeProject(Project $project): void
    {
        abort_unless((int) $project->user_id === (int) auth()->id(), 403);
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }

    private function uploadFile($file, string $subfolder): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/' . $subfolder);
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time() . '-' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/' . $subfolder . '/' . $filename;
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
