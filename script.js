function nextStep() {
  const beruf = document.getElementById("beruf").value;
  if (!beruf) {
    alert("Bitte wähle deinen Beruf aus.");
    return;
  }
  document.getElementById("step1").style.display = "none";
  document.getElementById("step2").style.display = "block";
}

document.getElementById("stepForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const ort = document.getElementById("ort").value;
  if (!ort) {
    alert("Bitte gib deinen Arbeitsort an.");
    return;
  }
  alert("Vielen Dank! Du erhältst deine Angebote in Kürze.");
});
