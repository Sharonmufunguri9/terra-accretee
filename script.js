const backdrop = document.getElementById('modalBackdrop');
const accessForm = document.getElementById('accessForm');
const projectForm = document.getElementById('projectForm');
const success = document.getElementById('success');
const nav = document.querySelector('.nav');
const menuToggle = document.querySelector('.menu-toggle');

function openModal(type){
  backdrop.classList.add('open');
  success.classList.add('hidden');
  accessForm.classList.toggle('hidden', type !== 'access');
  projectForm.classList.toggle('hidden', type !== 'project');
  document.body.style.overflow = 'hidden';
}
function closeModal(){
  backdrop.classList.remove('open');
  document.body.style.overflow = '';
}
document.querySelectorAll('[data-modal]').forEach(btn => {
  btn.addEventListener('click', () => openModal(btn.dataset.modal));
});
document.querySelector('.modal-close').addEventListener('click', closeModal);
document.getElementById('successClose').addEventListener('click', closeModal);
backdrop.addEventListener('click', e => { if(e.target === backdrop) closeModal(); });
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });

document.querySelectorAll('.demo-form').forEach(form => {
  form.addEventListener('submit', () => {
    accessForm.classList.add('hidden');
    projectForm.classList.add('hidden');
    success.classList.remove('hidden');
  });
});

menuToggle.addEventListener('click', () => {
  const open = nav.classList.toggle('mobile-open');
  menuToggle.setAttribute('aria-expanded', open);
});
document.querySelectorAll('.nav-links a').forEach(a => a.addEventListener('click', () => nav.classList.remove('mobile-open')));

// Lightweight reveal-on-scroll for section content.
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.style.animation = 'rise .7s ease both';
      observer.unobserve(entry.target);
    }
  });
},{threshold:.08});
document.querySelectorAll('.section, .loop-section, .sector-card, .opportunity, .value-list > div, .layers > div').forEach(el => observer.observe(el));
