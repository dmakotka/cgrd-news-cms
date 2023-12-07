# Project Title: CMS News Application

## Overview
This CMS (Content Management System) News Application allows users to manage news articles, including creating, reading, updating, and deleting news entries. It features a login system, and an admin area for news management.

## Features
- User authentication system.
- CRUD (Create, Read, Update, Delete) operations for news articles.
- AJAX-powered news editing and deletion for a seamless user experience.

## Setup and Installation

### Requirements
- PHP 7.4 or higher.
- MySQL 5.7 or higher.
- Composer for PHP dependency management.

### Installation Steps
1. **Clone the Repository:**
```
git clone https://github.com/dmakotka/cgrd-news-cms.git
```

2. **Navigate to the Project Directory:**
```
cd [path/to/your-project-directory]
```

3. **Install Dependencies:**
```
composer install
```

4. **Database Setup:**
- Create a MySQL database named `cms`.
- Import the provided SQL file to set up the required tables.
5. **Configuration:**
- Edit the `config/database.php` to set your database connection details.

### Running the Application
1. Start your local server within the project directory.
2. Access the application via `localhost` or the configured virtual host.

## Core Functionalities

### User Authentication
- Users can log in using predefined credentials.
- Incorrect login attempts display error messages.

### News Management
- **Create:** Add new news articles through a form in the admin area.
- **Read:** View a list of all news articles.
- **Update:** Edit news articles using AJAX-powered forms without refreshing the page.
- **Delete:** Remove news articles with confirmation prompts.
