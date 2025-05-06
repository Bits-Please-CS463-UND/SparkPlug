/**
 * Definition for handled responses from the server. Strictly useful for type-hinting in
 * IDEs such as PhpStorm.
 */
interface HandledResponse {
    title: string;
    message: string;
    sentinel: string;
    responseType: string;
}

/**
 * List response. Simply requires an unordered list to be shown in the modal.
 */
interface ListResponse extends HandledResponse{
    listContents: string[];
}

/**
 * Redirect response. Used to draw the response, then redirect once the user dismisses the modal.
 */
interface RedirectResponse extends HandledResponse{
    url: string;
}

/**
 * Secret response. Has an additional field for showing a monospaced value. Good for generated passwords.
 */
interface SecretResponse extends HandledResponse{
    secret: string;
}

/**
 * Flash item. Used to report errors when loading a fresh page.
 */
interface Flash {
    type: string;
    message: string;
}

/**
 * Just all the [redacted] [redacted]. who cares!
 */
interface VehicleData {
    id: string;
    make: string;
    model: string;
    year: number;
    color: string;
    currentLocation: ?GPSLocation;
    locationHistory: GPSLocation[];
    geofence: ?Geofence;
}

interface User {
    id: string;
    firstName: string;
    lastName: string;
    email: string;
    phoneNum: string;
}

interface ApplicationNotification {
    id: string;
    title: string;
    message: string;
    priority: string;
}

interface GPSLocation {
    lat: number;
    lng: number;
}

interface Geofence {
    radius: number;
    center: GPSLocation;
}

interface SeedResponse extends HandledResponse {
    vehicles: vehicleData;
    profile: User;
    notifications: ApplicationNotification[];
}