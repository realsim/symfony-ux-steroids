import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = {
        id: String,
        url: String,
        targetFrame: String,
    }

    initialize() {
        document.addEventListener('turbo:before-fetch-response', e => this.beforeFetchResponse(e))
    }

    beforeFetchResponse(e) {
        const fetchResponse = e.detail.fetchResponse;
        const redirectLocation = fetchResponse.response.headers.get('Turbo-Modal-Location');
        if (!redirectLocation) {
            return;
        }

        e.preventDefault();
        Turbo.clearCache();
        Turbo.visit(redirectLocation);
    }

    show(e) {
        e.preventDefault()

        fetch(this.urlValue, {
            method: 'GET',
            mode: 'same-origin',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Turbo-Modal-Target-Frame': this.targetFrameValue,
                'Turbo-Modal-ID': this.idValue,
            },
        })
            .then((response) => {
                return response.text()
            })
            .then((html) => {
                const template = document.createElement('template')
                template.innerHTML = html
                const frame = template.content.firstChild;
                frame.setAttribute('data-modal', true)

                const outdated = document.getElementById(frame.getAttribute('id'))
                if (outdated) {
                    document.body.removeChild(outdated)
                }

                document.body.append(frame)
            })
    }

    dismiss(e) {
        e.preventDefault()

        const frame = e.target.closest('[data-modal]')
        document.body.removeChild(frame)
    }
}
