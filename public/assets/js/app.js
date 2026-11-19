(() => {
  const storedTheme = localStorage.getItem('schoolhub-theme') || 'light';
  document.documentElement.setAttribute('data-bs-theme', storedTheme);

  document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
    const next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', next);
    localStorage.setItem('schoolhub-theme', next);
  });

  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.querySelector('[data-toggle-sidebar]');
  const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');
  const sidebarIcon = sidebarToggle?.querySelector('[data-sidebar-icon]');
  const mobileSidebar = window.matchMedia('(max-width: 992px)');
  const setSidebarOpen = (open) => {
    sidebar?.classList.toggle('open', open);
    if (sidebarBackdrop) {
      sidebarBackdrop.hidden = !open;
    }
    sidebarToggle?.setAttribute('aria-expanded', String(open));
    sidebarToggle?.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
    sidebarIcon?.classList.toggle('bi-list', !open);
    sidebarIcon?.classList.toggle('bi-x-lg', open);
  };

  sidebarToggle?.addEventListener('click', () => {
    setSidebarOpen(!sidebar?.classList.contains('open'));
  });
  sidebarBackdrop?.addEventListener('click', () => setSidebarOpen(false));
  sidebar?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      if (mobileSidebar.matches) {
        setSidebarOpen(false);
      }
    });
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && sidebar?.classList.contains('open')) {
      setSidebarOpen(false);
      sidebarToggle?.focus();
    }
  });
  mobileSidebar.addEventListener('change', (event) => {
    if (!event.matches) {
      setSidebarOpen(false);
    }
  });

  document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!window.confirm(form.dataset.confirm || 'Continue?')) {
        event.preventDefault();
      }
    });
  });

  const invoiceSelect = document.getElementById('invoice_id');
  const balanceOutput = document.getElementById('payment-balance');
  const amountInput = document.getElementById('amount_paid');
  const updatePaymentBalance = () => {
    const option = invoiceSelect?.selectedOptions[0];
    const balance = Number(option?.dataset.balance || 0);
    if (balanceOutput) {
      balanceOutput.textContent = `Outstanding balance: KES ${balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }
    if (amountInput) {
      amountInput.max = balance || '';
    }
  };
  invoiceSelect?.addEventListener('change', updatePaymentBalance);
  updatePaymentBalance();

  const chart = document.getElementById('dashboardChart');
  if (chart && window.Chart) {
    new Chart(chart, {
      type: 'bar',
      data: {
        labels: ['Students', 'Teachers', 'Parents', 'Fees / 1000'],
        datasets: [{
          label: 'Current Snapshot',
          data: [
            Number(chart.dataset.students || 0),
            Number(chart.dataset.teachers || 0),
            Number(chart.dataset.parents || 0),
            Math.round(Number(chart.dataset.fees || 0) / 1000)
          ],
          backgroundColor: ['#2563eb', '#16a34a', '#0891b2', '#f59e0b'],
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
      }
    });
  }
})();
