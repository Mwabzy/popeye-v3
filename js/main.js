/* =============================================
   POPEYE TOURS — MAIN JAVASCRIPT
   ============================================= */

function createPlannerHTML() {
  return `
        <!-- Step 1 -->
        <div class="planner-step active" data-step="1">
            <div class="step-header">
                <h3 class="step-title">Where &amp; what type?</h3>
                <div class="step-dots">
                    <div class="dot active"></div><div class="dot"></div>
                </div>
            </div>
            <div class="planner-grid">
                <div class="form-group">
                    <label>Destinations (Multi-select)</label>
                    <div class="pill-group dest-pills">
                        <div class="pill" data-val="Kenya">Kenya</div>
                        <div class="pill" data-val="Tanzania">Tanzania</div>
                        <div class="pill" data-val="Zanzibar">Zanzibar</div>
                        <div class="pill" data-val="Uganda">Uganda</div>
                        <div class="pill" data-val="Rwanda">Rwanda</div>
                        <div class="pill" data-val="Seychelles">Seychelles</div>
                        <div class="pill" data-val="Surprise me">Surprise me</div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Trip Type (Select one)</label>
                    <div class="pill-group type-pills">
                        <div class="pill selected" data-val="Day trip">Day trip</div>
                        <div class="pill" data-val="Multi-day safari">Multi-day safari</div>
                        <div class="pill" data-val="Beach escape">Beach escape</div>
                        <div class="pill" data-val="City break">City break</div>
                        <div class="pill" data-val="Mixed">Mixed</div>
                    </div>
                </div>
            </div>
            <div class="step-nav">
                <div></div>
                <button class="btn-next js-next">Next Step</button>
            </div>
        </div>

        <!-- Step 2: How to reach you -->
        <div class="planner-step" data-step="2">
            <div class="step-header">
                <h3 class="step-title">How to reach you?</h3>
                <div class="step-dots">
                    <div class="dot active"></div><div class="dot active"></div>
                </div>
            </div>
            <div class="planner-grid">
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" class="input-styled planner-name" placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Budget Per Person (USD)</label>
                    <select class="select-styled planner-budget">
                        <option value="Under $300">Under $300</option>
                        <option value="$300-$800">$300–$800</option>
                        <option value="$800-$2,000">$800–$2,000</option>
                        <option value="$2,000+">$2,000+</option>
                        <option value="Flexible" selected>Flexible</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preferred Channel</label>
                    <div class="channel-toggle">
                        <div class="channel-btn whatsapp active" data-channel="wa">WhatsApp</div>
                        <div class="channel-btn email" data-channel="em">Email</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="contact-label">WhatsApp Number</label>
                    <input type="text" class="input-styled planner-contact" placeholder="+1 234 567 8900">
                </div>
            </div>

            <div class="step-nav" style="margin-top: 1.5rem">
                <button class="btn-back js-back">Back</button>
                <button class="btn-next js-send planner-send-btn">Start conversation</button>
            </div>
        </div>
    `;
}

function initPlanner(container) {
  container.innerHTML = createPlannerHTML();

  const steps = container.querySelectorAll(".planner-step");
  let currentStep = 1;

  const state = {
    destinations: [],
    type: "Day trip",
    name: "",
    budget: "Flexible",
    channel: "wa",
    contact: "",
  };

  // Step Navigation
  container.querySelectorAll(".js-next:not(.js-send)").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (currentStep >= steps.length) return;
      steps[currentStep - 1].classList.remove("active");
      currentStep++;
      steps[currentStep - 1].classList.add("active");
    });
  });

  container.querySelectorAll(".js-back").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (currentStep === 1) return;
      steps[currentStep - 1].classList.remove("active");
      currentStep--;
      steps[currentStep - 1].classList.add("active");
    });
  });

  // Pills Multi-select
  const destPills = container.querySelectorAll(".dest-pills .pill");
  destPills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pill.classList.toggle("selected");
      const val = pill.dataset.val;
      if (state.destinations.includes(val)) {
        state.destinations = state.destinations.filter((d) => d !== val);
      } else {
        state.destinations.push(val);
      }
    });
  });

  // Pills Single-select
  const setupSingleSelect = (selector, stateKey) => {
    const pills = container.querySelectorAll(`${selector} .pill`);
    pills.forEach((pill) => {
      pill.addEventListener("click", () => {
        pills.forEach((p) => p.classList.remove("selected"));
        pill.classList.add("selected");
        state[stateKey] = pill.dataset.val;
      });
    });
  };
  setupSingleSelect(".type-pills", "type");

  // Inputs Sync
  container.querySelector(".planner-name").addEventListener("input", (e) => {
    state.name = e.target.value;
  });
  container.querySelector(".planner-budget").addEventListener("change", (e) => {
    state.budget = e.target.value;
  });
  container.querySelector(".planner-contact").addEventListener("input", (e) => {
    state.contact = e.target.value;
  });

  // Channel Toggle
  const channelBtns = container.querySelectorAll(".channel-btn");
  const contactLabel = container.querySelector(".contact-label");
  const contactInput = container.querySelector(".planner-contact");
  const sendBtn = container.querySelector(".js-send");

  channelBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      channelBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      state.channel = btn.dataset.channel;

      if (state.channel === "em") {
        contactLabel.textContent = "Email Address";
        contactInput.placeholder = "john@example.com";
        contactInput.type = "email";
        sendBtn.style.background = "var(--orange)";
      } else {
        contactLabel.textContent = "WhatsApp Number";
        contactInput.placeholder = "+1 234 567 8900";
        contactInput.type = "text";
        sendBtn.style.background = "#25D366";
      }
    });
  });

  // Build message
  const buildMsg = () => {
    const dest = state.destinations.length > 0 ? state.destinations.join(", ") : "East Africa";
    return `Hi Popeye Tours! 👋\n\nI'm ${state.name || "(Your Name)"} and I'm looking to plan a ${state.type.toLowerCase()} in ${dest}.\n\n💰 Budget: ${state.budget}\n\nMy contact: ${state.contact || "(Provided)"}\n\nCan you help me build this trip?`;
  };

  // Send
  sendBtn.addEventListener("click", () => {
    const msg = buildMsg();
    if (state.channel === "wa") {
      const url = `https://wa.me/254726875876?text=${encodeURIComponent(msg)}`;
      window.open(url, "_blank");
    } else {
      const subject = `Trip inquiry: ${state.type} in ${state.destinations.join(", ") || "East Africa"}`;
      const stepNav = sendBtn.closest(".step-nav");

      const showSendError = (errMsg) => {
        sendBtn.disabled = false;
        sendBtn.textContent = "Start conversation";
        let errEl = stepNav.querySelector(".planner-error");
        if (!errEl) {
          errEl = document.createElement("p");
          errEl.className = "planner-error";
          errEl.style.cssText = "color:red;margin:0.5rem 0 0;font-size:0.875rem;width:100%;";
          stepNav.appendChild(errEl);
        }
        errEl.textContent = errMsg;
      };

      sendBtn.disabled = true;
      sendBtn.textContent = "Sending…";

      const formData = new FormData();
      formData.append("action", "popeye_inquiry");
      formData.append("nonce", popeyeAjax.nonce);
      formData.append("name", state.name);
      formData.append("contact", state.contact);
      formData.append("subject", subject);
      formData.append("body", msg);

      fetch(popeyeAjax.url, { method: "POST", body: formData })
        .then((r) => r.json())
        .then((data) => {
          if (data.success) {
            stepNav.innerHTML =
              '<p class="planner-sent" style="margin:0;font-weight:600;">Message sent! We\'ll be in touch soon.</p>';
          } else {
            showSendError(
              (data.data && data.data.message) || "Something went wrong. Please try again.",
            );
          }
        })
        .catch(() => showSendError("Something went wrong. Please try again."));
    }
  });

  // Style send button based on default channel
  if (sendBtn) {
    sendBtn.style.background = "#25D366";
    sendBtn.style.width = "100%";
    sendBtn.style.maxWidth = "250px";
    sendBtn.style.textAlign = "center";
    sendBtn.style.display = "flex";
    sendBtn.style.justifyContent = "center";
  }

  return {
    setDestination: (dest) => {
      state.destinations = [dest];
      destPills.forEach((p) => {
        if (p.dataset.val === dest) {
          p.classList.add("selected");
        } else {
          p.classList.remove("selected");
        }
      });
    },
    resetStep: () => {
      currentStep = 1;
      steps.forEach((s, idx) => {
        if (idx === 0) s.classList.add("active");
        else s.classList.remove("active");
      });
    },
  };
}

// Initialize on DOM ready
document.addEventListener("DOMContentLoaded", () => {
  // Initialize Planners
  const inlinePlannerEl = document.getElementById("inline-planner");
  const modalPlannerEl = document.getElementById("modal-planner");

  let inlinePlanner, modalPlanner;
  if (inlinePlannerEl) inlinePlanner = initPlanner(inlinePlannerEl);
  if (modalPlannerEl) modalPlanner = initPlanner(modalPlannerEl);

  // Modal logic
  const modalOverlay = document.getElementById("modal-overlay");
  if (modalOverlay) {
    const openModal = (dest = null) => {
      modalOverlay.classList.add("active");
      document.body.style.overflow = "hidden";
      if (modalPlanner) {
        modalPlanner.resetStep();
        if (dest) modalPlanner.setDestination(dest);
      }
    };
    const closeModal = () => {
      modalOverlay.classList.remove("active");
      document.body.style.overflow = "auto";
    };

    document.querySelectorAll("[data-open-planner]").forEach((el) => {
      el.addEventListener("click", (e) => {
        e.preventDefault();
        const dest = el.dataset.openPlanner;
        openModal(dest === "true" ? null : dest);
      });
    });

    const closeBtn = document.querySelector(".modal-close");
    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    modalOverlay.addEventListener("click", (e) => {
      if (e.target === modalOverlay) closeModal();
    });
  }

  // Hero Carousel
  const slides = document.querySelectorAll(".hero-slide");
  if (slides.length > 0) {
    let currentSlide = 0;
    setInterval(() => {
      slides[currentSlide].classList.remove("active");
      currentSlide = (currentSlide + 1) % slides.length;
      slides[currentSlide].classList.add("active");
    }, 5000);
  }

  // Hamburger Menu
  const hamburgerBtn = document.getElementById("hamburger-menu");
  const navLinks = document.querySelector(".nav-links");

  if (hamburgerBtn && navLinks) {
    hamburgerBtn.addEventListener("click", () => {
      hamburgerBtn.classList.toggle("active");
      navLinks.classList.toggle("active");
    });

    navLinks.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        hamburgerBtn.classList.remove("active");
        navLinks.classList.remove("active");
      });
    });

    document.addEventListener("click", (e) => {
      if (!hamburgerBtn.contains(e.target) && !navLinks.contains(e.target)) {
        hamburgerBtn.classList.remove("active");
        navLinks.classList.remove("active");
      }
    });
  }

  // Scroll Effect for Navigation
  const nav = document.querySelector(".nav");
  if (nav) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        nav.classList.add("scrolled");
      } else {
        nav.classList.remove("scrolled");
      }
    });
  }

  // ── Services Carousel ──────────────────────────────────────────────────────
  const servicesTrack = document.getElementById("services-track");
  if (servicesTrack) {
    const prevBtn = document.getElementById("services-prev");
    const nextBtn = document.getElementById("services-next");
    let currentIndex = 0;

    function getVisibleCount() {
      const vw = window.innerWidth;
      if (vw >= 1100) return 4;
      if (vw >= 768)  return 2;
      return 1;
    }

    function getCards() {
      return Array.from(servicesTrack.querySelectorAll(".service-card"));
    }

    function getOffset() {
      const cards = getCards();
      if (!cards.length) return 0;
      const firstCard = cards[0];
      const style = getComputedStyle(servicesTrack);
      const gap = parseFloat(style.gap) || 32;
      return firstCard.offsetWidth + gap;
    }

    function updateCarousel() {
      const cards = getCards();
      const visible = getVisibleCount();
      const maxIndex = Math.max(0, cards.length - visible);

      currentIndex = Math.min(currentIndex, maxIndex);

      const offset = currentIndex * getOffset();
      servicesTrack.style.transform = `translateX(-${offset}px)`;

      if (prevBtn) prevBtn.disabled = currentIndex <= 0;
      if (nextBtn) nextBtn.disabled = currentIndex >= maxIndex;
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        if (currentIndex > 0) {
          currentIndex -= 1;
          updateCarousel();
        }
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        const cards = getCards();
        const maxIndex = Math.max(0, cards.length - getVisibleCount());
        if (currentIndex < maxIndex) {
          currentIndex += 1;
          updateCarousel();
        }
      });
    }

    let resizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(updateCarousel, 120);
    });

    updateCarousel();
  }

  // ── Journal Carousel ───────────────────────────────────────────────────────
  const journalTrack = document.getElementById("journal-track");
  if (journalTrack) {
    const journalPrev = document.getElementById("journal-prev");
    const journalNext = document.getElementById("journal-next");
    let journalIndex = 0;

    function getJournalVisible() {
      return window.innerWidth >= 768 ? 2 : 1;
    }

    function getJournalCards() {
      return Array.from(journalTrack.querySelectorAll(".blog-card"));
    }

    function getJournalOffset() {
      const cards = getJournalCards();
      if (!cards.length) return 0;
      const gap = parseFloat(getComputedStyle(journalTrack).gap) || 32;
      return cards[0].offsetWidth + gap;
    }

    function updateJournal() {
      const cards = getJournalCards();
      const visible = getJournalVisible();
      const maxIndex = Math.max(0, cards.length - visible);
      journalIndex = Math.min(journalIndex, maxIndex);
      journalTrack.style.transform = `translateX(-${journalIndex * getJournalOffset()}px)`;
      if (journalPrev) journalPrev.disabled = journalIndex <= 0;
      if (journalNext) journalNext.disabled = journalIndex >= maxIndex;
    }

    if (journalPrev) {
      journalPrev.addEventListener("click", () => {
        if (journalIndex > 0) { journalIndex -= 1; updateJournal(); }
      });
    }
    if (journalNext) {
      journalNext.addEventListener("click", () => {
        const maxIndex = Math.max(0, getJournalCards().length - getJournalVisible());
        if (journalIndex < maxIndex) { journalIndex += 1; updateJournal(); }
      });
    }

    let journalResizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(journalResizeTimer);
      journalResizeTimer = setTimeout(updateJournal, 120);
    });

    updateJournal();
  }
});
