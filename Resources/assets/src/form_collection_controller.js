import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = {
        addEmptyRow: Boolean,
        maxRows: Number,
        disabledButtonClasses: String,
    }
    static targets = [
        'collection',
        'button',
    ]

    entriesCount;

    connect() {
        this.entriesCount = this.collectionTarget.children.length || 0

        // Default values
        this.addEmptyRowValue = this.addEmptyRowValue || false
        this.maxRowsValue = this.maxRowsValue || 1000
        this.disabledButtonClassesValue = this.disabledButtonClassesValue || 'disabled'

        if (this.entriesCount === 0 && this.isEmptyRowRequired) {
            this.add()
        }

        if (!this.canAddRow) {
            this.toggleDisabledButtonClasses()
        }
    }

    get isEmptyRowRequired() {
        return this.addEmptyRowValue === true
    }

    get canAddRow() {
        return this.maxRowsValue > this.entriesCount;
    }

    toggleDisabledButtonClasses() {
        if (this.hasButtonTarget) {
            this.disabledButtonClassesValue.split(/\s+/).forEach(className => {
                this.buttonTarget.classList.toggle(className)
            })
        }
    }

    add(e) {
        if (e) {
            e.preventDefault()
        }

        if (!this.canAddRow) {
            return
        }

        const collection = this.collectionTarget
        const proto = collection.getAttribute('data-prototype')
        const template = document.createElement('template')
        template.innerHTML = proto.replace(/__name__/g, this.entriesCount).trim()
        const widget = template.content.firstChild
        widget.setAttribute('data-entry-id', this.entriesCount)
        collection.appendChild(widget)
        this.entriesCount++

        if (!this.canAddRow) {
            this.toggleDisabledButtonClasses()
        }
    }

    remove(e) {
        e.preventDefault()

        const entry = e.target.closest('[data-entry-id]')
        const collection = entry.parentNode
        this.entriesCount--
        collection.removeChild(entry)

        if (this.canAddRow) {
            this.toggleDisabledButtonClasses()
        }
    }
}
