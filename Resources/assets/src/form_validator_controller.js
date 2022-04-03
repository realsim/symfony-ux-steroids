import { Controller } from 'stimulus'

export default class extends Controller {
    static values = {
        url: String,
    }

    static targets = [
        'form',
        'submit',
        'input',
    ]

    static classes = ['valid', 'invalid']

    validate() {
        const formData = new FormData()

        this.inputTargets.forEach(target => {
            const inputName = target.getAttribute('name')
            formData.append(inputName, target.value)
        })

        fetch(this.urlValue, {
            method: 'POST',
            mode: 'same-origin',
            credentials: 'same-origin',
            body: formData,
        })
            .then(response => response.json())
            .then(json => {
                let isValid = true

                if (json.valid !== true) {
                    this.inputTargets.forEach(target => {
                        const inputName = target.getAttribute('name')
                        if (inputName in json.errors) {
                            isValid = false
                            target.classList.add(...this.invalidClass.split(' '))
                            target.classList.remove(...this.validClass.split(' '))
                        } else {
                            target.classList.add(...this.validClass.split(' '))
                            target.classList.remove(...this.invalidClass.split(' '))
                        }
                    })
                }

                this.formTarget.setAttribute('disabled', isValid ? '' : 'disabled')
            })
    }
}
