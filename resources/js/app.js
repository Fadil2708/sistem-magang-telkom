import Alpine from 'alpinejs';

// Score calculator for evaluation form
function scoreCalc() {
    return {
        scores: { soft_skill: 75, hard_skill: 75, attendance: 75, attitude: 75 },
        finalScore: 75,
        grade: 'B',
        gradeClass: 'B',
        calc() {
            const s = this.scores;
            this.finalScore = Math.round(
                (s.soft_skill * 0.25) +
                (s.hard_skill * 0.35) +
                (s.attendance * 0.20) +
                (s.attitude * 0.20)
            );
            this.grade = this.finalScore >= 85 ? 'A'
                       : this.finalScore >= 70 ? 'B'
                       : this.finalScore >= 55 ? 'C' : 'D';
            this.gradeClass = this.grade;
        }
    }
}

// Testimonial carousel
function testiCarousel() {
    return {
        current: 0,
        autoPlay: null,
        init() {
            this.startAutoPlay();
            this.$el.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.prev();
                if (e.key === 'ArrowRight') this.next();
            });
            this.$el.setAttribute('tabindex', '0');
            this.$el.setAttribute('role', 'region');
            this.$el.setAttribute('aria-label', 'Testimonial carousel');
        },
        startAutoPlay() {
            this.autoPlay = setInterval(() => {
                this.current = (this.current + 1) % this.$el.querySelectorAll('.testi-slide').length;
            }, 5000);
        },
        stopAutoPlay() {
            if (this.autoPlay) clearInterval(this.autoPlay);
            this.autoPlay = null;
        },
        goTo(i) {
            this.stopAutoPlay();
            this.current = i;
            this.startAutoPlay();
        },
        prev() {
            const total = this.$el.querySelectorAll('.testi-slide').length;
            this.goTo(this.current === 0 ? total - 1 : this.current - 1);
        },
        next() {
            const total = this.$el.querySelectorAll('.testi-slide').length;
            this.goTo((this.current + 1) % total);
        }
    }
}

// Skill picker for intern profile
function skillPicker() {
    return {
        open: false,
        search: '',
        selected: [],
        allSkills: [],
        init(initialSkills, skillsList) {
            this.selected = initialSkills || [];
            this.allSkills = skillsList || [];
        },
        getName(id) {
            const s = this.allSkills.find(s => s.id == id);
            return s ? s.name : id;
        },
        removeSkill(id) {
            this.selected = this.selected.filter(s => s !== id);
            this.syncSelected();
        },
        sync(event) {
            this.syncSelected();
        },
        syncSelected() {
            this.$wire.set('selectedSkills', this.selected);
        }
    }
}

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.data('scoreCalc', scoreCalc);
    Alpine.data('testiCarousel', testiCarousel);
    Alpine.data('skillPicker', skillPicker);
    Alpine.start();
} else {
    window.Alpine.data('scoreCalc', scoreCalc);
    window.Alpine.data('testiCarousel', testiCarousel);
    window.Alpine.data('skillPicker', skillPicker);
}

// Global toast helper — callable from any JS code
window.showToast = function (message, type = 'success') {
    window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
};

// Scroll reveal — supports [data-reveal], [data-reveal-stagger], [data-reveal-left], [data-reveal-right]
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                el.classList.add('revealed');
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll(
        '[data-reveal], [data-reveal-left], [data-reveal-right], [data-reveal-stagger]'
    ).forEach(el => observer.observe(el));
});
