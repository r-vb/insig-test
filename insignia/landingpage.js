window.addEventListener("scroll", () => {
  const triggerHeight = 220;
  if (window.scrollY > triggerHeight) {
    document.body.classList.add("scrolled");
  } else {
    document.body.classList.remove("scrolled");
  }
});

const daysEl = document.getElementById("days");
const hoursEl = document.getElementById("hours");
const minutesEl = document.getElementById("minutes");
const secondsEl = document.getElementById("seconds");

// Set target date: 13 days from now
const now = new Date().getTime();
const countdownDate = now + (13 * 24 * 60 * 60 * 1000); // 13 days in milliseconds

function updateCountdown() {
const currentTime = new Date().getTime();
const distance = countdownDate - currentTime;

if (distance < 0) {
  clearInterval(countdownInterval);
  daysEl.textContent = "00";
  hoursEl.textContent = "00";
  minutesEl.textContent = "00";
  secondsEl.textContent = "00";
  return;
}

const days = Math.floor(distance / (1000 * 60 * 60 * 24));
const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
const seconds = Math.floor((distance % (1000 * 60)) / 1000);

daysEl.textContent = String(days + ":").padStart(2, '0');
hoursEl.textContent = String(hours + ":").padStart(2, '0');
minutesEl.textContent = String(minutes + ":").padStart(2, '0');
secondsEl.textContent = String(seconds).padStart(2, '0');
}

const countdownInterval = setInterval(updateCountdown, 1000);
updateCountdown();