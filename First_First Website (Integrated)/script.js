// Five Simple JavaScript Interactions
function showName() {
  var name = document.getElementById('nameInput').value;
  document.getElementById('nameOutput').innerHTML = "Hello " + name + "!";
}

var count = 0;
function addOne() {
  count++;
  document.getElementById('counter').innerHTML = count;
}
function subtractOne() {
  count--;
  document.getElementById('counter').innerHTML = count;
}

function toggleVisibility() {
  var text = document.getElementById('toggleText');
  if (text.style.display === 'none') {
    text.style.display = 'block';
  } else {
    text.style.display = 'none';
  }
}

function changeBackground(color) {
  document.body.style.backgroundColor = color;
}

function changeTextOnHover() {
  document.getElementById('hoverText').innerHTML = "Slowly but surely :)";
}
function resetText() {
  document.getElementById('hoverText').innerHTML = "Hover the mouse here to change the text.";
}

// JavaScript Concepts Showcase
// 1. Variables
let name = "Marc";
let age = 20;
document.getElementById("variablesOutput").textContent =
  "Name: " + name + "\nAge: " + age;

// 2. Operators
let a = 10, b = 3;
let sum = a + b;
let product = a * b;
document.getElementById("operatorsOutput").textContent =
  "a = " + a + ", b = " + b + "\n" +
  "a + b = " + sum + "\n" +
  "a * b = " + product;

// 3. Conditional Statement
let grade = 85;
let result;
if (grade >= 90) {
  result = "Excellent!";
} else if (grade >= 75) {
  result = "Passed.";
} else {
  result = "Failed.";
}
document.getElementById("conditionOutput").textContent =
  "Grade: " + grade + "\nResult: " + result;

// 4. Loops
let loopText = "";
for (let i = 1; i <= 5; i++) {
  loopText += "Number " + i + "\n";
}
document.getElementById("loopsOutput").textContent = loopText;

// 5. Functions
function square(num) {
  return num * num;
}
let num = 6;
document.getElementById("functionsOutput").textContent =
  "square(" + num + ") = " + square(num);

// 6. Objects
let student = {
  firstName: "Marc",
  lastName: "Developer",
  age: 20,
  fullName: function() {
    return this.firstName + " " + this.lastName;
  }
};
document.getElementById("objectsOutput").textContent =
  "First Name: " + student.firstName + "\n" +
  "Last Name: " + student.lastName + "\n" +
  "Age: " + student.age + "\n" +
  "Full Name (via method): " + student.fullName();