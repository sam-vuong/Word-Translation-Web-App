function validate_reg(form) {
  fail = validateUsername(form.username.value);
  fail += validatePassword(form.password.value);
  if (fail == "") return true;
  else { alert(fail); return false; }
}

function validate_text(form) {
  if (form.input.value == "") {
    alert("Nothing was entered!"); return false;
  }
  else return true;
}

function validateUsername(field) {
  if (field == "") return "No username was entered.\n";
  else if (field.length < 2)
    return "Usernames must be at least 2 characters.\n";
  return "";
}

function validatePassword(field) {
  if (field == "") return "No password was entered.\n";
  else if (field.length < 3)
    return "Passwords must be at least 3 characters.\n";
  return "";
}
