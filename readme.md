# Art & Culture Platform

A dynamic web platform dedicated to promoting art and culture, allowing users to publish and explore articles across various domains including painting, music, literature, cinema, and more.

## 🌟 Features

### User Management
- Secure user registration and authentication with profile pictures
- Role-based access control (Admin, Author, Reader)
- Profile management and settings
- Automatic welcome emails for new users
- User favorites system

### Article Management
- Create, edit, and delete articles
- Category-based organization
- Multi-tag support
- Required featured images
- Admin approval system
- Pagination and filtering
- Comments and likes functionality
- PDF download option
- Advanced search by keywords or author

### Admin Dashboard
- User profile management and moderation
- Category and tag management
- Article moderation and approval
- Comment oversight
- Soft delete functionality for user management

## 🛠 Technologies

- **Backend:** PHP 8 (Object-Oriented Programming)
- **Database:** MySQL with PDO
- **Frontend:** Responsive Design with CSS Framework
- **Security:** XSS & CSRF protection, SQL injection prevention
- **Version Control:** Git

## 📋 Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer
- Git

## 🚀 Installation

1. Clone the repository
```bash
git clone https://github.com/your-username/art-culture-platform.git
```

2. Navigate to project directory
```bash
cd art-culture-platform
```

3. Install dependencies
```bash
composer install
```

4. Create database and import SQL schema
```bash
mysql -u your_username -p your_database < database/schema.sql
```

5. Start the development server
```bash
php -S localhost:8000
```

## 🔒 Security Features

- SQL Injection prevention through prepared statements
- XSS protection with input sanitization
- CSRF token validation
- Secure password hashing
- Form validation (Frontend & Backend)

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)