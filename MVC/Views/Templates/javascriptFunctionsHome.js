<script  type="text/javascript">
let x = document.getElementById("HomeAnmelden");
x.addEventListener("focus", myFocusMailadresse, true);
x.addEventListener("blur", myBlurMailadresse, true);
x.addEventListener("focus", myFocusMitgliedsnummer, true);
x.addEventListener("blur", myBlurMitgliedsnummer, true);
x.addEventListener("focus", myFocusDAVPasswort, true);
x.addEventListener("blur", myBlurDAVPasswort, true);

function myFocusMailadresse() {
  document.getElementById("Mailadresse").style.backgroundColor = "yellow";
}

function myBlurMailadresse() {
  document.getElementById("Mailadresse").style.backgroundColor = "";
}

function myFocusMitgliedsnummer() {
  document.getElementById("Mitgliedsnummer").style.backgroundColor = "yellow";
}

function myBlurMitgliedsnummer() {
  document.getElementById("Mitgliedsnummer").style.backgroundColor = "";
}

function myFocusDAVPasswort() {
  document.getElementById("DAVPasswort").style.backgroundColor = "yellow";
}

function myBlurDAVPasswort() {
  document.getElementById("DAVPasswort").style.backgroundColor = "";
}

</script>