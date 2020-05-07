export class SammelstelleSelectedEvent extends CustomEvent {

    constructor(sammelstelleId) {
        super('sammelstelle-selected', {
            detail: sammelstelleId
        });
    }

    get sammelstelleId() {
        return this.detail;
    }

}