# FileLink-POLSL

## Project Description

**FileLink-POLSL** is a web application based on **Symfony**, enabling secure file transfer between users. The project utilizes **filelinkSend** (formerly Firefox Send) and **WebSockets** for real-time communication.

The PHP server and MySQL database are running in a **Docker** environment, ensuring application isolation and simplifying deployment.

## Features

- Secure file transfer between users.
- File management through **Symfony API**.
- **WebSockets** for real-time notifications.
- **File encryption** before transfer (optional).
- Storing file metadata in a **MySQL database**.

## Requirements

- **Docker** + **Docker Compose**
- **PHP 8.1+**
- **Symfony 6+**
- **MySQL 8+**

## Installation

1. **Clone the repository**
   ```sh
   git clone https://github.com/BialySztorm/FileLink.git
   cd FileLink
   ```

2. **Install PHP dependencies**
   ```sh
   composer install
   ```

3. **Run the application in Docker**
   ```sh
   docker-compose up -d
   ```

## Configuration

The project docker test runs on HTTP and WebSocket on port 1443. For proper operation, ensure these settings are also configured in filelinkSend.

## Architecture

- **Backend:** Symfony (PHP)
- **Database:** MySQL (Docker)
- **Real-time communication:** WebSocket (PHP WebSocket Server)
- **Containerization:** Docker + Docker Compose

## Authors

- **Andrzej Manderla** – [am305303@polsl.pl](mailto:am305303@polsl.pl)

## License

This project is for the purpose of completing the **Web and Network Applications** course.