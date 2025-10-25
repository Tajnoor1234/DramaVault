# AI Chatbot Feature - Setup Guide

## Overview
The DramaVault AI Chatbot uses Google's Gemini AI to help users find drama/movie recommendations based on your website's content.

## Features
- 🤖 AI-powered recommendations
- 💬 Natural language conversation
- 🎬 Personalized drama/movie suggestions
- ⚡ Real-time responses
- 📱 Mobile responsive design

## Setup Instructions

### 1. Get Gemini API Key

1. Visit [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with your Google account
3. Click "Get API Key" or "Create API Key"
4. Copy your API key

### 2. Configure Environment

1. Open your `.env` file
2. Find the line: `GEMINI_API_KEY=your_gemini_api_key_here`
3. Replace `your_gemini_api_key_here` with your actual API key

Example:
```
GEMINI_API_KEY=AIzaSyC-abc123def456ghi789jkl012mno345pqr
```

### 3. Clear Cache (Important!)

After adding the API key, run:
```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test the Chatbot

1. Log in to your DramaVault account
2. Look for the purple robot icon in the bottom-right corner
3. Click to open the chatbot
4. Try asking: "What are the top-rated dramas?"

## Usage Examples

Users can ask questions like:
- "What are the top-rated dramas?"
- "Recommend a romantic comedy drama"
- "Show me recent action movies"
- "What thriller series do you have?"
- "Suggest something similar to The King: Eternal Monarch"
- "I want to watch a fantasy drama"

## Features

### Smart Recommendations
- AI analyzes your database of dramas/movies
- Provides relevant suggestions based on:
  - Genres
  - Ratings
  - User preferences
  - Recent additions

### Conversation Memory
- Each chat session maintains context
- Natural back-and-forth conversation
- Understands follow-up questions

### Visual Recommendations
- Shows drama cards with:
  - Poster image
  - Title
  - Type (Drama/Movie/Series)
  - Rating
  - Direct link to view details

## Troubleshooting

### API Key Issues
**Error: "Failed to get response from AI"**
- Check if your API key is correct
- Ensure you've run `php artisan config:clear`
- Verify the API key is active in Google AI Studio

### Rate Limits
- Free tier: 60 requests per minute
- If you hit limits, wait a minute and try again
- Consider upgrading for higher limits

### No Recommendations Found
- The AI will only recommend dramas that exist in your database
- If your database is empty, add some dramas first

## API Limits (Free Tier)

- **Requests per minute**: 60
- **Requests per day**: 1,500
- **Tokens per request**: 32,000 input / 8,000 output

For production use with high traffic, consider:
1. Implementing request caching
2. Rate limiting per user
3. Upgrading to a paid plan

## Security Notes

- API key is stored in `.env` (never commit to git)
- Only authenticated users can access the chatbot
- Responses are validated and sanitized
- User messages are limited to 1000 characters

## Customization

### Changing AI Model
Edit `app/Http/Controllers/ChatbotController.php`:
```php
private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
```

Available models:
- `gemini-pro` (default) - Best for text
- `gemini-pro-vision` - For image analysis
- `gemini-1.5-pro` - Latest version (if available)

### Adjusting Response Length
In `ChatbotController.php`, modify:
```php
'generationConfig' => [
    'temperature' => 0.7,  // Creativity (0-1)
    'maxOutputTokens' => 500,  // Max response length
]
```

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Verify API key is working in Google AI Studio
3. Ensure all routes are registered correctly

## Credits

- **AI Model**: Google Gemini
- **UI Design**: Custom Bootstrap implementation
- **Icons**: Font Awesome
