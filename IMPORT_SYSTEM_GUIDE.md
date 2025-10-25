# 🎬 Drama Import System - Complete Guide

## ✅ What's Been Created

Your DramaVault now has a powerful **API-based import system** that can automatically add movies and TV shows to your database!

---

## 🚀 How to Access

1. **Login as Admin**
   - Email: `admin@dramavault.com`
   - Password: `password`

2. **Navigate to Import Page**
   - Go to Admin Dashboard
   - Click the **"Import"** button (green button next to "Add New")
   - Or visit: `http://localhost:8000/admin/import`

---

## 🎯 Features

### 1️⃣ Quick Import Trending (Automated)

**Import multiple trending shows at once!**

- Choose type: TV Shows or Movies
- Select limit: 5, 10, or 20 items
- Click "Import Trending"
- System will:
  - Get trending content from Trakt.tv
  - Fetch detailed info from OMDb
  - Create dramas with posters, ratings, cast
  - Auto-create genres and cast members
  - Show progress and results

**Perfect for:** Quickly populating your database with popular content

---

### 2️⃣ Search & Import (Manual Selection)

**Search and import specific content!**

- Enter search query (e.g., "Breaking Bad", "Avengers")
- Choose source: OMDb or Trakt.tv
- Click "Search"
- Browse results with posters
- Click "Import" on any result
- System imports that specific show/movie

**Perfect for:** Adding specific content you want

---

## 📋 What Gets Imported

Each imported drama includes:

- ✅ **Title** & Original Title
- ✅ **Synopsis/Plot**
- ✅ **Release Year**
- ✅ **Country**
- ✅ **Type** (Movie/Series/Drama)
- ✅ **Episodes Count**
- ✅ **Duration**
- ✅ **IMDb Rating**
- ✅ **High-Quality Poster**
- ✅ **IMDb ID** (for reference)
- ✅ **Genres** (auto-created if new)
- ✅ **Cast Members** (top 5 actors, auto-created)

---

## 🔑 Your OMDb API Key

You've successfully tested your OMDb API key! Here's the response you got:

```json
{
  "Title": "Guardians of the Galaxy Vol. 2",
  "Year": "2017",
  "Type": "movie",
  "imdbRating": "7.6",
  "imdbID": "tt3896198"
}
```

This means your API is working perfectly! ✅

---

## 📚 Usage Examples

### Example 1: Import Guardians of the Galaxy Vol. 2

Since you already tested this movie, let's import it:

1. Go to `http://localhost:8000/admin/import`
2. In the search box, type: `Guardians of the Galaxy Vol 2`
3. Select source: `OMDb`
4. Click "Search"
5. Find the result (2017, Chris Pratt)
6. Click "Import"
7. Done! Movie is now in your database

### Example 2: Import Top 10 Trending Shows

1. Go to import page
2. In "Quick Import Trending" section:
   - Type: `TV Shows`
   - Limit: `10`
3. Click "Import Trending"
4. Wait for progress bar
5. Done! 10 trending shows imported automatically

### Example 3: Search on Trakt, Import from OMDb

1. Search box: `Breaking Bad`
2. Source: `Trakt.tv`
3. Click "Search"
4. See Trakt results with IMDb IDs
5. Click "Import" - uses OMDb for detailed data
6. Done! Best of both APIs combined

---

## 🎨 User Interface Features

### Search Results Display
- **Card layout** with posters
- **Type badges** (movie/series)
- **Year information**
- **Hover effects** (cards lift on hover)
- **Responsive design** (works on mobile)

### Import Progress
- **Loading spinners** during search
- **Modal overlay** during import
- **Success notifications** with SweetAlert
- **Progress bars** for batch imports
- **Detailed results** (imported/skipped/failed)

---

## 🔒 Security & Validation

### Duplicate Prevention
- System checks IMDb ID before importing
- Won't import same movie/show twice
- Shows message if already exists

### Error Handling
- API failures handled gracefully
- Database transactions (rollback on error)
- User-friendly error messages
- Rate limiting protection (200ms delay between requests)

### Admin Only
- Only users with `role = 'admin'` can access
- Protected by auth and admin middleware
- CSRF token protection on all forms

---

## 💡 Pro Tips

### 1. Use Trending Import for Bulk Content
- Import 10-20 trending shows to quickly populate your site
- Run weekly to keep content fresh

### 2. Search Both Sources
- OMDb: Better for specific title searches
- Trakt: Better for finding popular/trending content

### 3. Check Before Importing
- Review search results before clicking import
- Check year to ensure correct version
- Verify IMDb rating

### 4. Monitor API Limits
- **OMDb Free**: 1,000 requests/day
- **Trakt Free**: Unlimited
- System uses 24-hour caching to reduce requests

---

## 🐛 Troubleshooting

### "No results found"
- Try different search terms
- Switch between OMDb and Trakt sources
- Check spelling

### "This drama already exists"
- Movie/show is already in database
- View it instead of re-importing
- Check Dramas list

### "Failed to fetch data from OMDb API"
- Check your API key in `.env`
- Verify OMDb API key is active
- Check daily limit (1,000 requests)

### Import button not responding
- Clear browser cache
- Check browser console for errors
- Verify you're logged in as admin

---

## 🔄 Rate Limiting

To protect your API keys and comply with free tier limits:

- **Search**: No rate limit (cached results)
- **Single Import**: No rate limit (1 request)
- **Batch Import**: 200ms delay between requests (max 5/second)
- **Caching**: 
  - OMDb data: 7-30 days
  - Trakt data: 6-24 hours
  - Reduces duplicate API calls

---

## 📊 Next Steps

After importing some content:

1. **View Imported Dramas**
   - Go to: `http://localhost:8000/dramas`
   - See all your imported content

2. **Check Admin Dashboard**
   - See updated statistics
   - "Recent Dramas" section shows imports

3. **Edit If Needed**
   - Click "Edit" on any drama
   - Update synopsis, add custom info
   - Upload better posters

4. **Let Users Rate & Comment**
   - Users can now rate imported dramas
   - Leave comments and reviews
   - Add to watchlists

---

## 🎬 Example Imports to Try

### Popular Movies
- "The Dark Knight"
- "Inception"
- "Interstellar"
- "Avengers Endgame"
- "Parasite"

### Popular TV Shows
- "Breaking Bad"
- "Game of Thrones"
- "Stranger Things"
- "The Crown"
- "The Office"

### Korean Dramas
- "Squid Game"
- "Crash Landing on You"
- "Itaewon Class"
- "Hotel Del Luna"

---

## ✨ What's Next?

You can now:

1. ✅ **Import content** from OMDb and Trakt
2. ✅ **Search and find** any movie or show
3. ✅ **Bulk import** trending content
4. ✅ **Auto-create** genres and cast members
5. ✅ **View imported dramas** on your site

### Future Enhancements
- Schedule automatic weekly imports
- Import from specific genres
- Import TV show seasons and episodes
- Sync ratings with IMDb
- Import cast member photos

---

## 🎉 Ready to Import!

Visit: **http://localhost:8000/admin/import**

Start with importing "Guardians of the Galaxy Vol. 2" since you already tested it! 🚀

