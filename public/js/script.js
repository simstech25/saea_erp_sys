function togglePassword(inputId, openEyeId, closedEyeId) {
  const pw = document.getElementById(inputId);
  const eyeOpen = document.getElementById(openEyeId);
  const eyeClosed = document.getElementById(closedEyeId);

  if (pw.type === 'password') {
    pw.type = 'text';
    eyeOpen.style.display = 'inline-block';
    eyeClosed.style.display = 'none';
  } else {
    pw.type = 'password';
    eyeOpen.style.display = 'none';
    eyeClosed.style.display = 'inline-block';
  }
}
