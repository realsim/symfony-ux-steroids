import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = [
        'source',
        'button',
    ]

    static values = {
        successLabel: String,
        htmlLabel: {
            type: Boolean,
            default: false,
        },
        successDuration: {
            type: Number,
            default: 2e3,
        },
    }

    originalLabel
    timeout

    connect() {
        if (!this.hasButtonTarget) {
            return
        }

        this.originalLabel = this.htmlLabelValue ? this.buttonTarget.innerHTML : this.buttonTarget.innerText;
    }

    copy(e) {
        e.preventDefault()

        this.sourceTarget.select()
        document.execCommand('copy')
        this.copied()
    }

    copied() {
        if (!this.hasButtonTarget) {
            return
        }

        if (this.timeout) {
            clearTimeout(this.timeout)
        }

        const successLabel = this.hasSuccessLabelValue ? this.successLabelValue : this.data.get('successContent')
        this.setButtonLabel(successLabel)

        this.timeout = setTimeout(() => {
            this.setButtonLabel(this.originalLabel)
        }, this.successDurationValue)
    }

    setButtonLabel(label) {
        if (!this.hasButtonTarget) {
            return
        }

        if (this.htmlLabelValue) {
            this.buttonTarget.innerHTML = label
        } else {
            this.buttonTarget.innerText = label
        }
    }
}
