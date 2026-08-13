class FormAccordionValidator {
    constructor(form, customValidationFields = {}, options = {}) {
        this.form = form;
        this.customValidators = customValidationFields;

        this.options = {
            onSuccess: options.onValidationSuccess || null,
            onError: options.onValidationFailure || null,
            preventDefaultSubmit: options.preventDefaultSubmit ?? true,
            errorClass: 'is-invalid',
            feedbackClass: 'invalid-feedback',
            ...options
        };

        this._listeners = new Map();

        this._bindMethods();
        this.init();
    }

    _bindMethods() {
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    init() {
        if (this.options.preventDefaultSubmit) {
            this.form.setAttribute('novalidate', '');
            this.form.addEventListener('submit', this.handleSubmit);
        }
        this._setupRealtimeValidation();
    }

    _setupRealtimeValidation() {
        Object.keys(this.customValidators).forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (!field) return;

            const handler = () => {
                if (field.classList.contains(this.options.errorClass)) {
                    this._validateSingleField(field, this.customValidators[fieldName]);
                }
            };

            ['input', 'change'].forEach(evt => {
                field.addEventListener(evt, handler);
                this._trackListener(field, evt, handler);
            });
        });
    }

    _trackListener(element, event, handler) {
        if (!this._listeners.has(element)) {
            this._listeners.set(element, []);
        }
        this._listeners.get(element).push({ event, handler });
    }

    _validateSingleField(field, validatorFn) {
        const result = validatorFn(field.value, field);
        if (result === true) {
            this._clearError(field);
            return true;
        }
        this._showError(field, typeof result === 'string' ? result : 'Campo inválido');
        return false;
    }

    _isFieldInvalid(field) {
        if (field.disabled || field.type === 'hidden') return false;

        if (field.type === 'checkbox' || field.type === 'radio') {
            const group = this.form.querySelectorAll(`[name="${field.name}"]`);
            const isRequired = Array.from(group).some(el => el.hasAttribute('required'));
            if (!isRequired) return false;

            const isChecked = Array.from(group).some(el => el.checked);
            return !isChecked;
        }

        return !field.checkValidity();
    }

    validateForm() {
        const errors = [];

        const nativeFields = this.form.querySelectorAll('input, select, textarea');
        nativeFields.forEach(field => {
            if (this._isFieldInvalid(field)) {
                this._showError(field, field.validationMessage || 'Preenchimento obrigatório');
                errors.push(field);
            } else {
                if (!this.customValidators[field.name]) {
                    this._clearError(field);
                }
            }
        });

        Object.entries(this.customValidators).forEach(([name, validatorFn]) => {
            const field = this.form.querySelector(`[name="${name}"]`);
            if (field && !this._validateSingleField(field, validatorFn)) {
                if (!errors.includes(field)) errors.push(field);
            }
        });

        return {
            isValid: errors.length === 0,
            firstInvalidField: errors[0] || null,
            allErrors: errors
        };
    }

    _showError(field, message) {
        field.classList.add(this.options.errorClass);

        let parent = field.parentElement;

        if (parent.classList.contains('input-group') || parent.classList.contains('form-floating')) {
            parent = parent.parentElement;
        }

        let feedback = parent.querySelector(`.${this.options.feedbackClass}`);

        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = this.options.feedbackClass;
            field.parentNode.appendChild(feedback);
        }

        feedback.textContent = message;
        feedback.style.display = 'block';
    }

    _clearError(field) {
        field.classList.remove(this.options.errorClass);
        const parent = field.closest('.input-group') || field.parentNode;
        const feedback = parent.querySelector(`.${this.options.feedbackClass}`);
        if (feedback) {
            feedback.textContent = '';
            feedback.style.display = 'none';
        }
    }

    async _expandAndFocus(field) {
        if (!field) return;

        const accordionItem = field.closest('.accordion-collapse');

        if (!accordionItem || accordionItem.classList.contains('show')) {
            this._focusElement(field);
            return;
        }

        const onShown = () => {
            accordionItem.removeEventListener('shown.bs.collapse', onShown);
            this._focusElement(field);
        };

        accordionItem.addEventListener('shown.bs.collapse', onShown);

        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(accordionItem);
        bsCollapse.show();
    }

    _focusElement(field) {
        field.focus({ preventScroll: true });
        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    handleSubmit(e) {
        if (this.options.preventDefaultSubmit) e.preventDefault();

        const result = this.validateForm();

        if (!result.isValid) {
            this._expandAndFocus(result.firstInvalidField);
            if (this.options.onError) this.options.onError(result);
        } else {
            if (this.options.onSuccess) {
                this.options.onSuccess(this.form);
            } else if (this.options.preventDefaultSubmit) {
                this.form.submit();
            }
        }
    }

    destroy() {
        if (this.options.preventDefaultSubmit) {
            this.form.removeEventListener('submit', this.handleSubmit);
            this.form.removeAttribute('novalidate');
        }

        this._listeners.forEach((events, element) => {
            events.forEach(({ event, handler }) => {
                element.removeEventListener(event, handler);
            });
        });
        this._listeners.clear();
    }
}

if (typeof module !== 'undefined' && module.exports) {
    module.exports = FormAccordionValidator;
} else {
    window.FormAccordionValidator = FormAccordionValidator;
}