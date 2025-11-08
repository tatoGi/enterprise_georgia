# Blog Platform

A full-featured blog platform built with Laravel featuring role-based access control, post moderation, comments system, and notifications.

## Features

### 🎯 Core Functionality
- **User Registration & Authentication** - All registered users start as "User" role
- **Post Management** - Create posts with title, photo, description, and category
- **Comments System** - Comment and reply to posts (nested subcomments)
- **Post Moderation** - Approval/rejection workflow for content quality
- **Notifications** - Real-time notifications for users and moderators
- **Auto-Approval** - Posts automatically approved after 2 hours if not moderated
- **Category Filtering** - Browse posts by category
- **Image Upload** - Support for post images with storage management

### 👥 User Roles

#### 1. User (Default Role)
- Create blog posts with title, photo, description, and category
- View all approved posts
- View own posts with status (pending/approved/rejected)
- Edit and delete own posts
- Write comments and replies on approved posts
- Edit own comments (marked as "edited")
- Delete own comments
- Receive notifications when posts are approved/rejected
- View personal notification history

#### 2. Moderator
- All User permissions
- Access moderation queue
- Approve or reject pending posts
- Receive notifications when new posts are submitted
- View all pending posts with full details

#### 3. Admin
- All Moderator permissions
- Manage all users
- Change user roles (User ↔ Moderator ↔ Admin)
- Delete any post from any user
- Full platform control

### 📋 Guest Capabilities
- View all approved posts
- Browse posts by category
- Read full post details
- See comments (cannot comment without login)
- Sort posts by date (newest first)

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd enterprise_georgia
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Setup database (configure `.env` with your database credentials)
```bash
php artisan migrate
php artisan db:seed
```

5. Create storage link
```bash
php artisan storage:link
```

6. Start development server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Test Accounts

Three test accounts are created by default:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Moderator | moderator@example.com | password |
| User | user@example.com | password |

## Auto-Approval Feature

Posts pending moderation are automatically approved after 2 hours.

### Run Scheduler (Development)
```bash
php artisan schedule:work
```

### Manual Trigger
```bash
php artisan posts:auto-approve
```

### Production Setup
Add to crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Key Workflows

### 1. User Creates Post
1. User writes post with title, category, description, and optional photo
2. Post submitted with status: `pending`
3. All moderators receive notification
4. User can view post in "My Posts" with pending status

### 2. Moderation Process
1. Moderator sees post in moderation queue
2. Moderator reviews and approves or rejects
3. User receives notification of decision
4. Approved posts appear on main feed
5. If not moderated within 2 hours, post auto-approves

### 3. Commenting
1. Any authenticated user can comment on approved posts
2. Users can reply to comments (nested structure)
3. Comment authors can edit (marked as "edited") or delete
4. Comments display with author name and timestamp

### 4. Admin Management
1. Admin accesses user management panel
2. Can change any user's role via dropdown
3. Can delete any post from any user
4. Changes take effect immediately

## Database Schema

### Users
- id, name, email, password, role (user/moderator/admin), timestamps

### Posts
- id, user_id, title, photo, description, category, status (pending/approved/rejected), submitted_at, timestamps

### Comments
- id, post_id, user_id, parent_id, content, is_edited, timestamps

### Notifications
- id, user_id, type, message, post_id, is_read, timestamps

## Tech Stack
- **Framework:** Laravel 11
- **Database:** MySQL/PostgreSQL/SQLite
- **Frontend:** Blade Templates with custom CSS
- **Authentication:** Laravel Auth
- **File Storage:** Laravel Storage (public disk)

## Project Structure
```
app/
├── Console/Commands/
│   └── AutoApprovePosts.php       # Auto-approval command
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php     # User & post management
│   │   ├── AuthController.php      # Login/Register
│   │   ├── CommentController.php   # Comment CRUD
│   │   ├── ModerationController.php # Post moderation
│   │   ├── NotificationController.php # Notifications
│   │   └── PostController.php      # Post CRUD
│   └── Middleware/
│       └── CheckRole.php           # Role authorization
└── Models/
    ├── User.php                    # User with role helpers
    ├── Post.php                    # Post model
    ├── Comment.php                 # Comment with nesting
    └── Notification.php            # User notifications

resources/views/
├── layouts/
│   └── app.blade.php              # Main layout
├── auth/                          # Login/Register views
├── posts/                         # Post views (index, show, create, edit, my)
├── moderation/                    # Moderation queue
├── admin/                         # Admin panel
└── notifications/                 # Notification center

routes/
└── web.php                        # All application routes
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
