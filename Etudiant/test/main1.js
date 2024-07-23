const userIcon = document.querySelector('.user-icon');
const userList = document.getElementById('user-list');

userIcon.addEventListener('mouseover', () => {
  userList.style.display = 'block';
});

userIcon.addEventListener('mouseout', () => {
  // Check if the mouse is still hovering over the list or its child elements
  if (!userList.contains(event.target)) {
    userList.style.display = 'none';
  }
});

userList.addEventListener('mouseover', (event) => {
  // Prevent the list from hiding when the mouse moves within the list
  userList.style.display = 'block';
});

userList.addEventListener('mouseout', (event) => {
  // Check if the mouse is leaving the list entirely, not just moving within it
  if (event.target === userList && !userList.contains(event.relatedTarget)) {
    userList.style.display = 'none';
  }
});

// ---------------------------------------------------------------------------------
const btn1 = document.querySelector('.btn1');
const edtList = document.getElementById('edt-list');

btn1.addEventListener('mouseover', () => {
  edtList.style.display = 'block';
});

btn1.addEventListener('mouseout', () => {
  // Check if the mouse is still hovering over the list or its child elements
  if (!edtList.contains(event.target)) {
    edtList.style.display = 'none';
  }
});

edtList.addEventListener('mouseover', (event) => {
  // Prevent the list from hiding when the mouse moves within the list
  edtList.style.display = 'block';
});

edtList.addEventListener('mouseout', (event) => {
  // Check if the mouse is leaving the list entirely, not just moving within it
  if (event.target === edtList && !edtList.contains(event.relatedTarget)) {
    edtList.style.display = 'none';
  }
});
// ---------------------------------------------------------------------------------
const btn2 = document.querySelector('.btn2');
const s = document.getElementById('semestre-list');

btn2.addEventListener('mouseover', () => {
  s.style.display = 'block';
});

btn2.addEventListener('mouseout', () => {
  // Check if the mouse is still hovering over the list or its child elements
  if (!s.contains(event.relatedTarget)) {
    s.style.display = 'none';
  }
});

s.addEventListener('mouseover', () => {
  // Prevent the list from hiding when the mouse moves within the list
  s.style.display = 'block';
});

s.addEventListener('mouseout', (event) => {
  // Check if the mouse is leaving the list entirely, not just moving within it
  if (event.target === s && !s.contains(event.relatedTarget)) {
    s.style.display = 'none';
  }
});
// ---------------------------------------------------------------------------------
const btn3 = document.querySelector('.btn3');
const notes = document.getElementById('notes-list');

btn3.addEventListener('mouseover', () => {
  notes.style.display = 'block';
});

btn3.addEventListener('mouseout', () => {
  // Check if the mouse is still hovering over the list or its child elements
  if (!notes.contains(event.relatedTarget)) {
    notes.style.display = 'none';
  }
});

notes.addEventListener('mouseover', () => {
  // Prevent the list from hiding when the mouse moves within the list
  notes.style.display = 'block';
});

notes.addEventListener('mouseout', (event) => {
  // Check if the mouse is leaving the list entirely, not just moving within it
  if (event.target === notes && !notes.contains(event.relatedTarget)) {
    notes.style.display = 'none';
  }
});
// ---------------------------------------------------------------------------------
const btn5 = document.querySelector('.btn5');
const devoirs = document.getElementById('devoirs-list');

btn5.addEventListener('mouseover', () => {
  devoirs.style.display = 'block';
});

btn5.addEventListener('mouseout', () => {
  // Check if the mouse is still hovering over the list or its child elements
  if (!devoirs.contains(event.relatedTarget)) {
    devoirs.style.display = 'none';
  }
});

devoirs.addEventListener('mouseover', () => {
  // Prevent the list from hiding when the mouse moves within the list
  devoirs.style.display = 'block';
});

devoirs.addEventListener('mouseout', (event) => {
  // Check if the mouse is leaving the list entirely, not just moving within it
  if (event.target === devoirs && !devoirs.contains(event.relatedTarget)) {
    devoirs.style.display = 'none';
  }
});
