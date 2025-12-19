# Voting Application

A PHP-based voting application built using **Object-Oriented Programming**, **PDO**, and **MySQL**.  
This project allows users to register, create topics, vote, and comment, with all data stored persistently in a database.

---

## Features

- User registration and authentication
- Topic creation by authenticated users
- Upvote/downvote system with duplicate-vote prevention
- Commenting system on topics
- Human-readable timestamps using a shared formatter
- MySQL database integration using PDO and dependency injection
- Structured using OOP principles (single responsibility, encapsulation)

---

## Tech Stack

- **PHP (OOP + PDO)**
- **MySQL**
- **HTML / CSS**
- **PHPUnit** (for testing)

---

## Project Structure

```text
project/
├── classes.php          # All application classes
├── db.config.php        # Database configuration (no credentials committed)
├── tables.sql           # Database schema
├── sqlbackup.txt        # Exported database backup
```

---

## Screenshots
- Home
<img width="1667" height="936" alt="Screenshot 2025-12-19 153249" src="https://github.com/user-attachments/assets/f66bc497-ada6-4f95-bac6-980d7687a273" />

- Register (error handling)
<img width="847" height="531" alt="Screenshot 2025-12-19 154412" src="https://github.com/user-attachments/assets/c344196f-d54c-4c51-a8b6-6423fa2c48a4" />
<img width="833" height="575" alt="Screenshot 2025-12-19 154432" src="https://github.com/user-attachments/assets/68d9468a-0b0b-47ac-bbfd-d5ee30214125" />

- Register (successful)
<img width="827" height="570" alt="Screenshot 2025-12-19 154450" src="https://github.com/user-attachments/assets/d1861127-2e42-45d9-84fd-29d833453ef0" />

-  Login
<img width="841" height="447" alt="Screenshot 2025-12-19 154517" src="https://github.com/user-attachments/assets/3dfd223a-d0a6-4b38-b3be-5c539c155fee" />

- Creating a Post / Topic
<img width="846" height="525" alt="Screenshot 2025-12-19 154547" src="https://github.com/user-attachments/assets/79a32d8c-8ad4-40d7-9b1a-f918b30c4244" />

- Upvoting / Downvoting / Commenting
<img width="782" height="797" alt="Screenshot 2025-12-19 154635" src="https://github.com/user-attachments/assets/836e6b96-3704-44b4-942d-55db5babbcd7" />


---

### Testing
- Unit tests are provided in the ``tests/`` directory
- Classes accept a PDO object via dependency injection
- Compatible with the provided ``VotingAppTest.php``

### Timestamp Formatting
All timestamps are formatted using the ``TimeFormatter`` utility class:
- Recent dates → ``X minutes/hours ago``
- Older than 12 months → ``M d, Y``

---

## Author
Rojan Jafarnezhad

