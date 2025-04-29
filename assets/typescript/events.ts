export class VehicleChangedEvent extends Event{
    vehicle: VehicleData;
    constructor(vehicle: VehicleData){
        super('vehicleChanged');
        this.vehicle = vehicle;
    }
}

export class EngineStatusChangedEvent extends Event{
    running: boolean;
    constructor(running: boolean){
        super('engineChanged');
        this.running = running;
    }
}