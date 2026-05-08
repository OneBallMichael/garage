// ── MENU TABS FUNCTIONALITY ──
document.addEventListener('DOMContentLoaded', function() {
  const tabs = document.querySelectorAll('.menu-tab');
  const tabContents = document.querySelectorAll('.menu-tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      const targetTab = this.getAttribute('data-tab');
      
      // Remove active class from all tabs and contents
      tabs.forEach(t => t.classList.remove('active'));
      tabContents.forEach(content => content.classList.remove('active'));
      
      // Add active class to clicked tab and corresponding content
      this.classList.add('active');
      document.getElementById(targetTab).classList.add('active');
    });
  });
});
