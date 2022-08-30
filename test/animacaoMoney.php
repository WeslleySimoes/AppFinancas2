<script>
function myCalculator() {
  $(".container2").show();
  var start = 1000;
  var end = 10000;
  var duration = 500;
  animateValue("value", start, end, duration); // remove .toLocaleString()
}

function animateValue(id, start, end, duration) {
  var range = end - start;
  var current = start;
  var increment = end > start ? 10 : -10;
  var stepTime = Math.abs(Math.floor(duration / range));
  var obj = document.getElementById(id);
  var timer = setInterval(function() {
    current += increment;
    obj.innerHTML = current.toLocaleString(); // add .toLocaleString() here
    if (current == end) {
      clearInterval(timer);
    }
  }, stepTime);
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<button type="button" onclick="myCalculator();">Click Here to See Animation</button>

<div class="container2" style="display:none;">
  Animating Amount $ <span id="value"></span>
</div>