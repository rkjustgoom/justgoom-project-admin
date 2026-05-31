/* JustGoom — Multi Calculator Hub */
document.addEventListener('DOMContentLoaded', function() {
  if (!document.querySelector('.calc-hub-layout')) return;
  initCalculatorHub();
});

function initCalculatorHub() {
  var sidebarItems = document.querySelectorAll('.calc-sidebar-item');
  var panels = document.querySelectorAll('.calc-panel');
  var resultTitle = document.getElementById('calcResultTitle');
  var resultBody = document.getElementById('calcResultBody');
  var resultNote = document.getElementById('calcResultNote');

  function showCalculator(id) {
    sidebarItems.forEach(function(item) {
      item.classList.toggle('active', item.dataset.calc === id);
    });
    panels.forEach(function(panel) {
      panel.classList.toggle('active', panel.id === 'panel-' + id);
    });
    resetResult();
    var activeItem = document.querySelector('.calc-sidebar-item[data-calc="' + id + '"]');
    if (resultTitle && activeItem) {
      resultTitle.textContent = activeItem.querySelector('.calc-sidebar-label').textContent + ' Result';
    }
  }

  function resetResult() {
    if (resultBody) {
      resultBody.innerHTML = '<div class="calc-result-placeholder"><span class="calc-result-icon">📊</span><p>Enter details and click Calculate to see your result here.</p></div>';
    }
    if (resultNote) resultNote.textContent = '';
  }

  window.setCalcResult = function(html, note) {
    if (resultBody) resultBody.innerHTML = html;
    if (resultNote) resultNote.textContent = note || '';
  };

  sidebarItems.forEach(function(item) {
    item.addEventListener('click', function() {
      showCalculator(item.dataset.calc);
    });
  });

  /* ── BMI ── */
  var bmiForm = document.getElementById('bmiForm');
  if (bmiForm) {
    var unit = 'metric';
    var unitBtns = bmiForm.querySelectorAll('.unit-toggle button');
    var heightInput = document.getElementById('height');
    var weightInput = document.getElementById('weight');
    var heightLabel = document.getElementById('heightLabel');
    var weightLabel = document.getElementById('weightLabel');

    unitBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        unitBtns.forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        unit = btn.dataset.unit;
        heightLabel.textContent = unit === 'metric' ? 'Height (cm)' : 'Height (inches)';
        weightLabel.textContent = unit === 'metric' ? 'Weight (kg)' : 'Weight (lbs)';
        heightInput.placeholder = unit === 'metric' ? 'e.g. 170' : 'e.g. 67';
        weightInput.placeholder = unit === 'metric' ? 'e.g. 70' : 'e.g. 154';
      });
    });

    bmiForm.querySelectorAll('.gender-option').forEach(function(opt) {
      opt.addEventListener('click', function() {
        bmiForm.querySelectorAll('.gender-option').forEach(function(o) { o.classList.remove('active'); });
        opt.classList.add('active');
      });
    });

    bmiForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var height = parseFloat(heightInput.value);
      var weight = parseFloat(weightInput.value);
      if (!height || !weight || height <= 0 || weight <= 0) {
        alert('Please enter valid height and weight.');
        return;
      }
      if (unit === 'imperial') {
        height = height * 2.54;
        weight = weight * 0.453592;
      }
      var heightM = height / 100;
      var bmi = weight / (heightM * heightM);
      var category, tip, cls;
      if (bmi < 18.5) { category = 'Underweight'; cls = 'underweight'; tip = 'Consider a balanced diet with adequate calories and protein.'; }
      else if (bmi < 25) { category = 'Normal Weight'; cls = 'normal'; tip = 'Great! Maintain regular exercise and a balanced diet.'; }
      else if (bmi < 30) { category = 'Overweight'; cls = 'overweight'; tip = 'Regular activity and portion control can help improve your BMI.'; }
      else { category = 'Obese'; cls = 'obese'; tip = 'Consult a healthcare professional for a personalized plan.'; }

      var markerPos = Math.min(Math.max((bmi / 40) * 100, 2), 98);
      setCalcResult(
        '<div class="calc-result-value">' + bmi.toFixed(1) + '</div>' +
        '<div class="calc-result-badge ' + cls + '">' + category + '</div>' +
        '<div class="bmi-scale" style="margin-top:20px;">' +
          '<div class="bmi-scale-bar"><div class="bmi-scale-marker" style="left:' + markerPos + '%;"></div></div>' +
          '<div class="bmi-scale-labels"><span>Under</span><span>Normal</span><span>Over</span><span>Obese</span></div>' +
        '</div>' +
        '<div class="calc-result-tip"><strong>Tip:</strong> ' + tip + '</div>',
        'WHO BMI classification'
      );
    });
  }

  /* ── Calorie (BMR) ── */
  var calorieForm = document.getElementById('calorieForm');
  if (calorieForm) {
    calorieForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var weight = parseFloat(document.getElementById('calWeight').value);
      var height = parseFloat(document.getElementById('calHeight').value);
      var age = parseFloat(document.getElementById('calAge').value);
      var gender = calorieForm.querySelector('.gender-option.active')?.dataset.gender || 'male';
      if (!weight || !height || !age) { alert('Please fill all fields.'); return; }

      var bmr = gender === 'male'
        ? 10 * weight + 6.25 * height - 5 * age + 5
        : 10 * weight + 6.25 * height - 5 * age - 161;

      setCalcResult(
        '<div class="calc-result-value">' + Math.round(bmr) + '</div>' +
        '<div class="calc-result-badge normal">BMR kcal/day</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Sedentary</span><strong>' + Math.round(bmr * 1.2) + ' kcal</strong></li>' +
          '<li><span>Light activity</span><strong>' + Math.round(bmr * 1.375) + ' kcal</strong></li>' +
          '<li><span>Moderate activity</span><strong>' + Math.round(bmr * 1.55) + ' kcal</strong></li>' +
          '<li><span>Very active</span><strong>' + Math.round(bmr * 1.725) + ' kcal</strong></li>' +
        '</ul>',
        'Mifflin-St Jeor equation · daily calorie needs'
      );
    });
    calorieForm.querySelectorAll('.gender-option').forEach(function(opt) {
      opt.addEventListener('click', function() {
        calorieForm.querySelectorAll('.gender-option').forEach(function(o) { o.classList.remove('active'); });
        opt.classList.add('active');
      });
    });
  }

  /* ── Loan EMI ── */
  var loanForm = document.getElementById('loanForm');
  if (loanForm) {
    loanForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var principal = parseFloat(document.getElementById('loanAmount').value);
      var rate = parseFloat(document.getElementById('loanRate').value);
      var tenure = parseFloat(document.getElementById('loanTenure').value);
      var tenureType = document.getElementById('loanTenureType').value;
      if (!principal || !rate || !tenure) { alert('Please fill all loan fields.'); return; }

      var months = tenureType === 'years' ? tenure * 12 : tenure;
      var r = rate / 12 / 100;
      var emi = r === 0 ? principal / months : principal * r * Math.pow(1 + r, months) / (Math.pow(1 + r, months) - 1);
      var total = emi * months;
      var interest = total - principal;

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(emi) + '</div>' +
        '<div class="calc-result-badge normal">Monthly EMI</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Principal</span><strong>₹' + formatINR(principal) + '</strong></li>' +
          '<li><span>Total Interest</span><strong>₹' + formatINR(interest) + '</strong></li>' +
          '<li><span>Total Payment</span><strong>₹' + formatINR(total) + '</strong></li>' +
          '<li><span>Tenure</span><strong>' + months + ' months</strong></li>' +
        '</ul>',
        'Indicative EMI · actual rates may vary by lender'
      );
    });
  }

  /* ── Gold ── */
  var goldForm = document.getElementById('goldForm');
  if (goldForm) {
    goldForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var weight = parseFloat(document.getElementById('goldWeight').value);
      var purity = parseFloat(document.getElementById('goldPurity').value);
      var rate = parseFloat(document.getElementById('goldRate').value);
      if (!weight || !rate) { alert('Please enter weight and gold rate.'); return; }

      var pureWeight = weight * (purity / 100);
      var value = (pureWeight / 10) * rate;

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(value) + '</div>' +
        '<div class="calc-result-badge normal">Estimated Gold Value</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Weight</span><strong>' + weight + ' grams</strong></li>' +
          '<li><span>Purity</span><strong>' + purity + '% (' + getKaratLabel(purity) + ')</strong></li>' +
          '<li><span>Pure gold content</span><strong>' + pureWeight.toFixed(2) + ' g</strong></li>' +
          '<li><span>Rate used</span><strong>₹' + formatINR(rate) + ' / 10g</strong></li>' +
        '</ul>',
        'Market rates change daily · for reference only'
      );
    });
  }

  /* ── Age ── */
  var ageForm = document.getElementById('ageForm');
  if (ageForm) {
    ageForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var dob = new Date(document.getElementById('dobInput').value);
      if (isNaN(dob.getTime())) { alert('Please select a valid date of birth.'); return; }
      var today = new Date();
      if (dob > today) { alert('Date of birth cannot be in the future.'); return; }

      var years = today.getFullYear() - dob.getFullYear();
      var months = today.getMonth() - dob.getMonth();
      var days = today.getDate() - dob.getDate();
      if (days < 0) { months--; days += new Date(today.getFullYear(), today.getMonth(), 0).getDate(); }
      if (months < 0) { years--; months += 12; }

      var totalDays = Math.floor((today - dob) / (1000 * 60 * 60 * 24));

      setCalcResult(
        '<div class="calc-result-value">' + years + '</div>' +
        '<div class="calc-result-badge normal">Years Old</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Exact age</span><strong>' + years + 'y ' + months + 'm ' + days + 'd</strong></li>' +
          '<li><span>Total months</span><strong>' + (years * 12 + months) + '</strong></li>' +
          '<li><span>Total days lived</span><strong>' + totalDays.toLocaleString('en-IN') + '</strong></li>' +
          '<li><span>Next birthday in</span><strong>' + daysUntilBirthday(dob) + ' days</strong></li>' +
        '</ul>',
        'Calculated from date of birth'
      );
    });
  }

  /* ── GST ── */
  var gstForm = document.getElementById('gstForm');
  if (gstForm) {
    gstForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var amount = parseFloat(document.getElementById('gstAmount').value);
      var rate = parseFloat(document.getElementById('gstRate').value);
      var mode = document.getElementById('gstMode').value;
      if (!amount || amount <= 0) { alert('Please enter a valid amount.'); return; }

      var gst, base, total;
      if (mode === 'add') {
        base = amount;
        gst = amount * rate / 100;
        total = amount + gst;
      } else {
        total = amount;
        base = amount / (1 + rate / 100);
        gst = total - base;
      }

      setCalcResult(
        '<div class="calc-result-value calc-result-currency">₹' + formatINR(total) + '</div>' +
        '<div class="calc-result-badge normal">Total Amount</div>' +
        '<ul class="calc-result-list">' +
          '<li><span>Base amount</span><strong>₹' + formatINR(base) + '</strong></li>' +
          '<li><span>GST @ ' + rate + '%</span><strong>₹' + formatINR(gst) + '</strong></li>' +
          '<li><span>Mode</span><strong>' + (mode === 'add' ? 'GST Exclusive' : 'GST Inclusive') + '</strong></li>' +
        '</ul>',
        'Indian GST calculation'
      );
    });
  }

  showCalculator('bmi');
}

function formatINR(num) {
  return Math.round(num).toLocaleString('en-IN');
}

function getKaratLabel(purity) {
  if (purity >= 99) return '24K';
  if (purity >= 91) return '22K';
  if (purity >= 75) return '18K';
  if (purity >= 58) return '14K';
  return purity + '% pure';
}

function daysUntilBirthday(dob) {
  var today = new Date();
  var next = new Date(today.getFullYear(), dob.getMonth(), dob.getDate());
  if (next <= today) next.setFullYear(today.getFullYear() + 1);
  return Math.ceil((next - today) / (1000 * 60 * 60 * 24));
}
