# Term Project Report 02
*Bits Please*  
*Julia Dewhurst, Joseph Melancon, Cade Wasti*  
*CS463, Dr. Emanuel Grant, UND*  
*03/23/2025*

## Progress Summary
During the reporting period for progress report 2, we have been working on updating the interface drawings and class 
diagrams, creating use case descriptions, activity diagrams and state charts. It was noted in the instructions that we 
only needed to create two use case descriptions, activity diagrams and state diagrams. The use case descriptions that 
were created covered the add/remove vehicle function and the view vehicle metrics function. Activity diagrams were 
created for the set current/primary driver and start vehicle functions. State charts were created for outlining which 
engine dependent metrics are displayed depending upon the ignition being on or off, and for outlining the behavior of 
metrics reporting that are dependent upon vehicle uplink. As we worked on these charts and diagrams, there were no 
issues encountered, and we will continue to work on successfully completing this project.

By the next reporting period, we will make adjustments to the class diagram that was created during this reporting 
period. The new class diagram for progress report three will include a more detailed solution description. We will also 
be working on sequence diagrams and a system dictionary. 

## Revised Class Diagram
![A UML Class Diagram modelling our vision for the requested system. The only change is a modification to the Priority enumerable’s association to the Notification interface to denote it as an associated class rather than an implementing class.](/docs/assets/report_02/figure_01.png)  
*Figure 1: A UML Class Diagram modelling our vision for the requested system. The only change is a modification to the Priority enumerable’s association to the Notification interface to denote it as an associated class rather than an implementing class.*

## Use Case Descriptions

| Key                           | Value                                                                                                                                 |
|-------------------------------|---------------------------------------------------------------------------------------------------------------------------------------|
| Use Case Title                | Add/Delete Vehicle                                                                                                                    |
| Goal in Context               | The user will be able to add or delete a vehicle from their account                                                                   |
| Scope and Level               | Users will have an account and be able to login and navigate to the add/delete vehicle option to add or remove a vehicle              |
| Preconditions                 | A subscriber is logged in and selects the option to add/remove vehicle                                                                |
| Success/Failure End Condition | Success: a logged in user successfully adds or removes a vehicle <br/> Failure: a logged in user is unable to add or remove a vehicle |
| Primary/Secondary Actors      | Primary: user; Secondary: system                                                                                                      |
| Trigger                       | Selects function to add or remove a vehicle                                                                                           |
| Description                   | Users logs in and navigates to the function to add or remove a vehicle                                                                |  

*Table 1: Vehicle addition/deletion use case description*

| Key                           | Value                                                                                                                                                                                                                |
|-------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Use Case Title                | View Vehicle Metrics                                                                                                                                                                                                 |
| Goal in Context               | Display a selected vehicle’s metrics                                                                                                                                                                                 |
| Scope and Level               | Users will have an account and be able to login and navigate to the view vehicle metrics function to display a selected vehicle’s metrics.                                                                           |
| Preconditions                 | A user is logged in and selects a vehicle                                                                                                                                                                            |
| Success/Failure End Condition | Success: a logged in user will be able to select a vehicle and view its metrics <br/> Failure: a logged in user selects a vehicle and selects the function to view its metrics and nothing is displayed to the user. |
| Primary/Secondary Actors      | Primary: user; Secondary: system                                                                                                                                                                                     |
| Trigger                       | A user is logged in, one of their vehicles is selected, and the function to view metrics is selected.                                                                                                                |
| Description                   | User logs in and selects a vehicle’s metrics to view.                                                                                                                                                                |  

*Table 2: Vehicle metrics viewing use case description*

## Activity Diagrams
![A UML activity diagram outlining the process required for a user to assign a primary driver](/docs/assets/report_02/figure_02.png)  
*Figure 2: A UML activity diagram outlining the process required for a user to assign a primary driver*

![A UML activity diagram outlining the steps to start a registered vehicle](/docs/assets/report_02/figure_03.png)  
*Figure 3: A UML activity diagram outlining the steps to start a registered vehicle*

## State Charts
![State chart outlining visibility of engine-dependent metrics in regards to ignition status](/docs/assets/report_02/figure_04.png)  
*Figure 4: State chart outlining visibility of engine-dependent metrics in regards to ignition status*

![State chart outlining behavior of metrics reporting dependent on vehicle uplink](/docs/assets/report_02/figure_05.png)  
*Figure 5: State chart outlining behavior of metrics reporting dependent on vehicle uplink*

## Interface Drawings
![Interface drawings. Icons are from the Font Awesome icon database, and this is the interface we plan to implement. Additionally, use of bold and oblique fonts is employed to add more depth to labels.](/docs/assets/report_02/figure_06.png)  
*Figure 6: Interface drawings. Icons are from the Font Awesome icon database, and this is the interface we plan to implement. Additionally, use of bold and oblique fonts is employed to add more depth to labels.*