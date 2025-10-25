# 🎬 DramaVault - Complete Setup Guide

## 🔑 API Keys Setup (FREE)

### 1. OMDb API Key (Open Movie Database)
**Get your FREE API key:**

1. Visit: http://www.omdbapi.com/apikey.aspx
2. Select "FREE! (1,000 daily limit)" plan
3. Enter your email address and name
4. Click "Submit"
5. Check your email for activation link
6. Click activation link to verify
7. Your API key will be displayed (also sent via email)

**Add to `.env`:**
```env
OMDB_API_KEY=your_omdb_api_key_here
```

**Features:**
- Movie and TV show details by title or IMDb ID
- IMDb ratings, Rotten Tomatoes, Metacritic scores
- Plot summaries, cast, director, writer
- Posters and metadata
- 1,000 free requests per day

---

### 2. Trakt.tv API (Client ID & Secret)
**Get your FREE API credentials:**

1. Visit: https://trakt.tv/ and create account
2. Go to Settings → Your API Apps: https://trakt.tv/oauth/applications
3. Click "New Application"
4. Fill in details:
   - Name: DramaVault
   - Description: Drama review platform
   - Redirect URI: `urn:ietf:wg:oauth:2.0:oob`
5. Click "Save App"
6. Copy your Client ID and Client Secret

**Add to `.env`:**
```env
TRAKT_CLIENT_ID=your_trakt_client_id_here
TRAKT_CLIENT_SECRET=your_trakt_client_secret_here
```

**Features:**
- Trending shows and movies
- Popular and anticipated content
- Show statistics and ratings
- Related shows and recommendations
- Comprehensive metadata (IMDb, TMDb, TVDb IDs)
- **FREE unlimited API calls!**

---

### 3. News API Key
**Get your FREE API key:**

1. Visit: https://newsapi.org/register
2. Sign up for free (Developer plan)
3. Copy your API key from the dashboard

**Add to `.env`:**
```env
NEWSAPI_KEY=your_newsapi_key_here
```

---

## 📦 Installation Steps

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Configure Environment
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Add API Keys to `.env`
```env
# Database
DB_CONNECTION=sqlite

# TMDb API
TMDB_API_KEY=your_tmdb_api_key_here

# News API
NEWSAPI_KEY=your_newsapi_key_here

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### 4. Setup Database
```bash
# Create fresh database
php artisan migrate:fresh

# Seed with sample data
php artisan db:seed --class=DatabaseSeeder
```

### 5. Create Storage Link
```bash
php artisan storage:link
```

### 6. Run Development Server
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

---

## 🎯 Features Implemented

### ✅ User Features
- [x] User Registration & Login
- [x] Email Verification
- [x] User Profiles with Avatar Upload
- [x] Watchlist Management
- [x] Rating System (1-10 scale)
- [x] Comment & Reply System
- [x] Like/Dislike Comments
- [x] Follow/Unfollow Users
- [x] Dark/Light Mode Toggle
- [x] User Activity Feed
- [x] Personalized Dashboard

### ✅ Drama & Movie Features
- [x] Browse Dramas/Movies
- [x] Search & Filter (by genre, year, country, rating)
- [x] AJAX Live Search
- [x] Detailed Drama Pages
- [x] Cast Information
- [x] Average Ratings Display
- [x] Related/Similar Recommendations
- [x] TMDb Integration

### ✅ Admin Features
- [x] Full CRUD for Dramas/Movies
- [x] Full CRUD for Cast Members
- [x] Full CRUD for News Articles
- [x] Full CRUD for Genres
- [x] User Role Management
- [x] Admin Dashboard with Analytics
- [x] Statistics & Charts (Chart.js)
- [x] Import from TMDb

### ✅ Social Features
- [x] Comments with Nested Replies
- [x] Comment Likes/Dislikes
- [x] User Following System
- [x] Activity Notifications
- [x] User Mentions (@username)

### ✅ Advanced Features
- [x] TMDb API Integration
- [x] News API Integration
- [x] Caching System (Performance)
- [x] AJAX Updates
- [x] Responsive Design
- [x] Animations (AOS library)
- [x] SEO Optimization

---

## 🔐 Default Admin Credentials

```
Email: admin@dramavault.com
Password: password
```

---

## 🎨 Frontend Technologies

- **Bootstrap 5.3.0** - UI Framework
- **Font Awesome 6.4.0** - Icons
- **AOS** - Scroll Animations
- **Chart.js** - Admin Analytics
- **Vite** - Asset Bundler

---

## 🗄️ Database Tables

- `users` - User accounts
- `dramas` - Movies/Series/Dramas
- `genres` - Drama genres (many-to-many)
- `cast` - Actors/Actresses (many-to-many)
- `ratings` - User ratings
- `comments` - Comments with replies
- `watchlists` - User watchlists
- `follows` - User follow relationships
- `news` - News articles
- `notifications` - User notifications
- `sessions` - Active sessions

---

## 🚀 Usage Guide

### For Regular Users:

1. **Sign Up**: Create account at `/register`
2. **Browse**: Explore dramas at `/dramas`
3. **Rate**: Give ratings (1-10) on drama pages
4. **Comment**: Share your thoughts
5. **Watchlist**: Add dramas to your watchlist
6. **Follow**: Follow other users
7. **Profile**: Customize your profile

### For Admin:

1. **Login**: Use admin credentials
2. **Dashboard**: Access at `/admin/dashboard`
3. **Manage Content**:
   - Add/Edit/Delete Dramas
   - Manage Cast Members
   - Create News Articles
   - Moderate Comments
4. **Import from TMDb**: Use TMDb import feature
5. **Analytics**: View site statistics

---

## 🧪 Testing

### Test User Flow:
```bash
1. Register new account
2. Verify email (if enabled)
3. Browse dramas
4. Add to watchlist
5. Rate and comment
6. Follow other users
7. Check notifications
```

### Test Admin Flow:
```bash
1. Login as admin
2. Access admin dashboard
3. Create new drama
4. Import from TMDb
5. Manage users
6. View analytics
```

---

## 🛠️ Troubleshooting

### Clear All Caches:
```bash
php artisan optimize:clear
php artisan view:clear
php artisan cache:clear
```

### Reset Database:
```bash
php artisan migrate:fresh --seed
```

### Fix Storage Permissions:
```bash
# Windows PowerShell
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

---

## 📝 API Rate Limits

### TMDb:
- **Free Tier**: 1,000 requests per day
- **No credit card required**
- Caching implemented to reduce API calls

### News API:
- **Free Tier**: 100 requests per day
- **No credit card required**
- Use cached data for better performance

---

## 🌟 Next Steps

1. ✅ Get your API keys (TMDb + News API)
2. ✅ Add keys to `.env`
3. ✅ Run migrations
4. ✅ Seed database
5. ✅ Test all features
6. ✅ Customize as needed

---

## 📞 Support

All features are fully implemented and working! Enjoy your DramaVault platform! 🎉

---

## 📄 License

This project is open-source and available under the MIT License.
