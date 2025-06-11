$(document).ready(function () {
  // Carousel functionality
  const slides = $('.evt-carousel-slide');
  const indicators = $('.evt-carousel-indicators button');
  let currentSlide = 0;

  function showSlide(index) {
    slides.removeClass('active').eq(index).addClass('active');
    indicators.removeClass('active').eq(index).addClass('active');
  }

  $('.evt-carousel-next').click(function () {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  });

  $('.evt-carousel-prev').click(function () {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
  });

  indicators.click(function () {
    currentSlide = $(this).index();
    showSlide(currentSlide);
  });

  // Auto-play carousel
  setInterval(function () {
    $('.evt-carousel-next').click();
  }, 5000);

  // Calendar functionality
  const dateGrid = $('.date-grid');
  const monthYearDisplay = $('.month-year-display');
  const prevMonthBtn = $('.prev-month');
  const nextMonthBtn = $('.next-month');
  const prevYearBtn = $('.prev-year');
  const nextYearBtn = $('.next-year');
  const monthTabs = $('.month-tab');
  let currentDate = new Date(2025, 4, 27); // May 27, 2025
  let events = [
    { date: '2025-05-27', title: 'Torneo Fortnite', type: 'live' },
    { date: '2025-05-27', title: 'Valorant Showdown', type: 'soon' },
    { date: '2025-05-27', title: 'Overwatch 2 Masters', type: 'featured' },
    { date: '2025-05-28', title: 'LAN Party CS2', type: 'soon' },
    { date: '2025-05-28', title: 'Rocket League Rumble', type: 'soon' },
    { date: '2025-05-28', title: 'Minecraft Survival Games', type: 'soon' },
    { date: '2025-05-10', title: 'Overwatch 2 Tournament', type: 'past' },
    { date: '2025-05-01', title: 'Minecraft Build Battle', type: 'past' },
    { date: '2025-04-25', title: 'Fortnite Champions Cup', type: 'past' },
  ];

  function renderCalendar(year, month) {
    dateGrid.empty();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const prevMonthDays = new Date(year, month, 0).getDate();
    const today = new Date(2025, 4, 27);
    const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    monthYearDisplay.text(`${monthNames[month]} ${year}`);
    monthTabs.removeClass('active-month').eq(month).addClass('active-month');

    // Add previous month days
    const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;
    for (let i = adjustedFirstDay - 1; i >= 0; i--) {
      dateGrid.append(`<div class="date-cell prev-month disabled">${prevMonthDays - i}</div>`);
    }

    // Add current month days
    for (let day = 1; day <= daysInMonth; day++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const isToday = year === today.getFullYear() && month === today.getMonth() && day === today.getDate();
      const dayEvents = events.filter(e => e.date === dateStr);
      let eventClass = '';
      let eventTooltip = '';
      if (dayEvents.length > 0) {
        eventClass = dayEvents.length > 1 ? 'multiple' : dayEvents[0].type;
        eventTooltip = dayEvents.map(e => e.title).join(', ');
      }
      const activeClass = isToday ? 'active-day' : '';
      dateGrid.append(`
        <div class="date-cell ${activeClass}" ${eventClass ? `data-events="${eventTooltip}"` : ''}>
          ${day}
          ${eventClass ? `<span class="event-dot ${eventClass}"></span>` : ''}
        </div>
      `);
    }

    // Add next month days
    const totalCells = adjustedFirstDay + daysInMonth;
    const remainingCells = Math.ceil(totalCells / 7) * 7 - totalCells;
    for (let i = 1; i <= remainingCells; i++) {
      dateGrid.append(`<div class="date-cell next-month disabled">${i}</div>`);
    }
  }

  prevMonthBtn.click(function () {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });

  nextMonthBtn.click(function () {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });

  prevYearBtn.click(function () {
    currentDate.setFullYear(currentDate.getFullYear() - 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });

  nextYearBtn.click(function () {
    currentDate.setFullYear(currentDate.getFullYear() + 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });

  monthTabs.click(function () {
    currentDate.setMonth($(this).data('month'));
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });

  renderCalendar(currentDate.getFullYear(), currentDate.getMonth());

  // Event filtering
  const gameFilter = $('#game-filter');
  const statusFilter = $('#status-filter');
  const typeFilter = $('#type-filter');
  const resetFiltersBtn = $('#reset-filters');
  const tabs = $('.evt-nav-link');
  const galleries = $('.evt-event-gallery');

  function filterEvents() {
    const game = gameFilter.val();
    const status = statusFilter.val();
    const type = typeFilter.val();
    const activeTab = tabs.filter('.active').data('tab');

    galleries.each(function () {
      const gallery = $(this);
      if (gallery.attr('id') !== activeTab) {
        gallery.attr('aria-hidden', 'true').hide();
        return;
      }
      gallery.attr('aria-hidden', 'false').show();

      const cards = gallery.find('.evt-event-card');
      let hasVisible = false;
      cards.each(function () {
        const card = $(this);
        const gameMatch = game === 'all' || card.find('h5').text().toLowerCase().includes(game);
        const statusMatch = status === 'all' || (status === 'upcoming' && card.find('.evt-event-badge').length && !card.find('.evt-join-btn').is(':disabled')) || (status === 'past' && card.find('.evt-join-btn').is(':disabled'));
        const typeMatch = type === 'all' || card.find('.evt-event-details').text().toLowerCase().includes(type);
        if (gameMatch && statusMatch && typeMatch) {
          card.show();
          hasVisible = true;
        } else {
          card.hide();
        }
      });
      $('#no-events').toggle(!hasVisible);
    });
  }

  gameFilter.change(filterEvents);
  statusFilter.change(filterEvents);
  typeFilter.change(filterEvents);

  tabs.click(function () {
    tabs.removeClass('active').attr('aria-selected', 'false');
    $(this).addClass('active').attr('aria-selected', 'true');
    filterEvents();
  });

  resetFiltersBtnizier
  gameFilter.val('all');
  statusFilter.val('all');
  typeFilter.val('all');
  filterEvents();

  // Back to top button
  const backToTop = $('.evt-back-to-top');
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      backToTop.addClass('visible');
    } else {
      backToTop.removeClass('visible');
    }
  });

  backToTop.click(function () {
    $('html, body').animate({ scrollTop: 0 }, 500);
  });
});