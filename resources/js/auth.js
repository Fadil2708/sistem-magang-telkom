import collapse from '@alpinejs/collapse';

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);

    window.Alpine.data('confirmModal', () => ({
        open: false,
        message: '',
        callback: null,
        show(msg, cb) {
            this.message = msg;
            this.callback = cb;
            this.open = true;
        },
        confirm() {
            if (this.callback) this.callback();
            this.open = false;
            this.callback = null;
        }
    }));

    window.Alpine.data('toastStack', (initialToasts = []) => ({
        toasts: [],
        init() {
            initialToasts.forEach(t => {
                const id = Date.now() + Math.random();
                this.toasts.push({ ...t, id });
            });
        },
        add(event) {
            const id = Date.now() + Math.random();
            this.toasts.push({
                id,
                message: event.detail.message,
                type: event.detail.type ?? 'success'
            });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }));

    window.Alpine.data('scoreCalc', () => ({
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
    }));

    window.Alpine.data('timedHide', () => ({
        show: true,
        init() {
            setTimeout(() => { this.show = false; }, 2000);
        }
    }));

    window.Alpine.data('skillPicker', () => ({
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
    }));
});

window.showToast = function (message, type = 'success') {
    window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
};

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
