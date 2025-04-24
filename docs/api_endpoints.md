# API Endpoints

Cade please implement 🙏

## Vehicle Location

### Get Current

Location: `/api/v1/vehicle/{vehicleId}/location/current`  
Method: `GET`  
Request Body: N/A  
Response Body:
```json
{
    "id": "...",
    "lat": 0.0,
    "lng": 0.0
}
```

### Set Current

Location: `/api/v1/vehicle/{vehicleId}/location/current`  
Method: `POST`  
Request Body: 
```json
{
    "lat": 0.0,
    "lng": 0.0
}
```
Response Body:
```json
{
    "id": "..."
}
```

### Get Location Log

Location: `/api/v1/vehicle/{vehicleId}/location/log`  
Method: `GET`  
Request Body: N/A  
Response Body:
```json
[
    {
        "id": "...",
        "lat": 0.0,
        "lng": 0.0
    }
]
```

### Get Geofence

Location: `/api/v1/vehicle/{vehicleId}/location/geofence`  
Method: `GET`  
Request Body: N/A  
Response Body:
```json
{
    "lat": 0.0,
    "lng": 0.0,
    "radius": 0.0
}
```

### Set Geofence

Location: `/api/v1/vehicle/{vehicleId}/location/geofence`  
Method: `POST`  
Request Body:  
```json
{
    "lat": 0.0,
    "lng": 0.0,
    "radius": 0.0
}
```

## Notifications

### Get Notifications

Location: `/api/v1/vehicle/{vehicleId}/notifications`  
Method: `GET`  
Request Body: N/A  
Response Body:  
```json
[
    {
        "id": "...",
        "title": "...",
        "message": "...",
        "priority": "...",
        "issuedAt": "2025-04-23T00:00:00.000Z"
    }
]
```
Note: Only notifications that have not been acknowledged should be returned.
`issuedAt` should be an ISO8601 string, which I believe Symfony serializes to by default.

### Acknowledge Notification

Location: `/api/v1/vehicle/{vehicleId}/notifications/{notificationId}`  
Method: `DELETE`  
Request Body: N/A  
Response Body: N/A

### Send Notification

Location: `/api/v1/vehicle/{vehicleId}/notifications`  
Method: `POST`  
Request Body: 
```json
{
    "title": "...",
    "message": "...",
    "priority": "..."
}
```
Response Body:
```json
{
    "id": "...",
    "issuedAt": "2025-04-23T00:00:00.000Z"
}
```

## Logs & Diagnostics

### Get Logs

Location: `/api/v1/vehicle/{vehicleId}/logs`  
Method: `GET`  
Request Body: N/A  
Response Body:
```json
[
    {
        "id": "...",
        "title": "...",
        "message": "...",
        "issuedAt": "2025-04-23T00:00:00.000Z"
    }
]
```
Note: Not sure how to account for this as it's not in our class diagram.
Unsure if needed.

### Add Log Entry

Location: `/api/v1/vehicle/{vehicleId}/logs`  
Method: `POST`  
Request Body:
```json
{
    "title": "...",
    "message": "..."
}
```
Response Body:
```json
{
    "id": "...",
    "issuedAt": "2025-04-23T00:00:00.000Z"
}
```

## User Management

### Grant Access to Vehicle

Location: `/api/v1/vehicle/{vehicleId}/drivers`  
Method: `POST`  
Request Body:
```json
{
    "email": "..."
}
```
Response Body:
```json
{
    "firstName": "...",
    "lastName": "..."
}
```

### Remove Vehicle Access

Location: `/api/v1/vehicle/{vehicleId}/drivers/{driverId}`  
Method: `DELETE`  
Request Body: N/A  
Response Body: N/A  