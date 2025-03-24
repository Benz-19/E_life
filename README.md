# E-Life

## Overview
E-Life is a mobile-friendly web application designed for university students to connect with medical doctors for online consultations. The app provides features such as real-time chat, appointment scheduling, medical prescriptions, and an integrated pharmacy system. If required, doctors can also schedule in-person consultations.

## Features
- **User Authentication:** Secure login and registration with email verification.
- **Real-time Chat:** Patients can communicate with doctors using a live chat system powered by Ratchet WebSocket.
- **Appointment Scheduling:** Doctors can manage and schedule physical appointments using an integrated calendar.
- **Medical Prescriptions:** Doctors can issue prescriptions digitally, which students can use at the university pharmacy.
- **Doctor Availability Status:** Doctors are marked as "busy" when in a consultation, preventing new patients from starting a chat.

## Technologies Used
- **Backend:** PHP
- **Frontend:** HTML, CSS (Tailwind CSS), JavaScript
- **Database:** MySQL
- **Real-time Communication:** Ratchet WebSocket
- **Packaging:** Cordova/WebView for mobile conversion

## Installation
### Prerequisites
- PHP 8+
- Composer
- MySQL
- Node.js & npm (for front-end dependencies if required)

### Steps
1. Clone the repository:
   ```sh
   git clone https://github.com/Benz-19/E_life.git
   cd E_life
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Set up environment variables:
   ```sh
   cp .env.example .env
   ```

## Usage
- Sign up as a student or doctor.
- Doctors can update their availability and chat with students.
- Students can request consultations and receive prescriptions.
- Admin can monitor and manage users through the dashboard.

## Contribution
Feel free to contribute to E-Life by submitting pull requests or reporting issues.

## License
This project is open-source and available under the MIT License.

---

**Maintained by:** Benz-19

