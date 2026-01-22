

# 📚 **YP Exam Portal**

A comprehensive online examination system built with Laravel for educational institutions. This platform enables lecturers to create and manage exams, while allowing students to take exams online with real-time auto-saving and time tracking.

## ✨ **Features**

### 👨‍🏫 **Lecturer Features**
- ✅ Create and manage classes with student enrollment
- ✅ Create and manage subjects
- ✅ Create and publish exams with multiple question types
- ✅ Multiple choice questions (MCQ) with dynamic options
- ✅ Open text/essay questions
- ✅ Real-time exam statistics and results tracking
- ✅ Publish/unpublish exams with access control

### 👨‍🎓 **Student Features**
- ✅ View available exams in enrolled classes
- ✅ Start exams with automatic time tracking
- ✅ Take exams with real-time auto-saving
- ✅ Multiple choice and open text question support
- ✅ Question navigation panel
- ✅ Exam results with detailed score breakdown
- ✅ Automatic submission on timeout

### 🛠️ **Technical Features**
- ✅ Role-based authentication (Lecturer/Student)
- ✅ Email verification
- ✅ Responsive design with dark/light mode
- ✅ Real-time timer with auto-submission
- ✅ Auto-saving answers
- ✅ Secure exam taking environment
- ✅ SQLite database (easy setup)

## 🚀 **Quick Start**

### **Prerequisites**
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite

### **Installation**

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/yp-exam-portal.git
   cd yp-exam-portal
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   - Edit `.env` file:
     ```env
     DB_CONNECTION=sqlite
     ```
   - Create database file:
     ```bash
     touch database/database.sqlite
     ```

5. **Run migrations and seed**
   ```bash
   php artisan migrate --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

8. **Visit the application**
   - Open `http://localhost:8000`
   - Login with default credentials:
     - **Lecturer**: lecturer@example.com / password
     - **Student**: student@example.com / password

## 📁 **Project Structure**

```
yp-exam-portal/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Lecturer/     # Lecturer controllers
│   │   │   └── Student/      # Student controllers
│   │   └── Middleware/       # Custom middleware
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/
│   │   ├── lecturer/        # Lecturer views
│   │   ├── student/         # Student views
│   │   └── layouts/         # Layout templates
│   └── js/                  # JavaScript files
├── routes/
│   └── web.php             # Application routes
└── tests/                  # Test files
```

## 🧪 **Testing Accounts**

### **Default Users**
| Role | Email | Password |
|------|-------|----------|
| Lecturer | lecturer@example.com | password |
| Student | student@example.com | password |

### **Create Additional Users**
```bash
php artisan db:seed --class=UserSeeder
```

## 🔧 **Development**

### **Running Tests**
```bash
php artisan test
```

### **Watching Assets**
```bash
npm run dev
```

### **Clearing Cache**
```bash
php artisan optimize:clear
```

## 📊 **Database Schema**

### **Main Tables**
- **users**: User accounts (lecturers & students)
- **classes**: Academic classes
- **subjects**: Course subjects
- **exams**: Examination papers
- **questions**: Exam questions
- **exam_attempts**: Student exam attempts
- **answers**: Student answers to questions

## 🔒 **Security Features**

- ✅ CSRF protection
- ✅ XSS protection
- ✅ SQL injection prevention
- ✅ Authentication middleware
- ✅ Role-based access control
- ✅ Email verification
- ✅ Session security
- ✅ Input validation
- ✅ Secure password hashing

## 🎨 **UI/UX Features**

- ✅ Responsive design (mobile-friendly)
- ✅ Dark/Light mode support
- ✅ Intuitive navigation
- ✅ Real-time feedback
- ✅ Loading states
- ✅ Error handling
- ✅ Accessibility considerations
- ✅ Clean, modern interface

## 📱 **Browser Support**

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 🤝 **Contributing**

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 **Acknowledgments**

- [Laravel](https://laravel.com) - The PHP framework
- [Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) - Authentication scaffolding
- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Alpine.js](https://alpinejs.dev) - JavaScript framework

## 📞 **Support**

For support, email support@example.com or create an issue in the GitHub repository.

---

**Made with ❤️ for educational institutions**

*Simplify online examinations, enhance learning experience.*
