document.addEventListener('DOMContentLoaded', () => {

  const container = document.getElementById('page');
  container.insertAdjacentHTML('afterEnd', '<div class="to-up">Back to top</div>');
  const toTopBtn = document.querySelector('.to-up');
  
  window.addEventListener('scroll', function () {
    toTopBtn.style.display = (window.pageYOffset > 500) ? 'block' : 'none';
  });
  
  toTopBtn.addEventListener('click', function () {
    window.scrollBy({
        top: -document.documentElement.scrollHeight,
        behavior: 'smooth'
    });
  });
});