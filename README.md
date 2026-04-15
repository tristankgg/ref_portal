# Signature Referrals Portal

A complete referral management system for Signature Appliances with user authentication, referral tracking, and profile management.

## Features

✅ **User Authentication**
- Login system with email and password
- Pre-configured credentials: tristankgg@gmail.com / Password123!
- Green gradient login screen (v0.22)

✅ **Referrals Management**
- Add new referrals with customer details (name, phone, business, email, notes)
- Edit existing referrals
- Soft delete (marks as "trash" instead of permanently deleting)
- View referrals in an organized table with status badges
- Filter out trash entries from main view

✅ **Profile Management**
- View and edit user profile
- Store contact information, business details, and ABN
- All fields are fully editable

✅ **Sidebar Navigation**
- Menu: Referrals, Rewards, Analytics
- Settings: My Profile
- Logout functionality

✅ **Data Persistence**
- Uses browser localStorage to save data locally
- Data persists across sessions

## File Structure

```
ref_portal/
├── index.html          # Complete web application
├── README.md           # This file
└── .gitignore          # Git ignore file
```

## Setup & Deployment

### Option 1: GitHub Pages (Recommended)

1. **Clone your repository:**
   ```bash
   git clone https://github.com/tristankgg/ref_portal.git
   cd ref_portal
   ```

2. **Add the files:**
   - Copy `index.html` to the root of your repository

3. **Commit and push:**
   ```bash
   git add index.html
   git commit -m "Add Signature Referrals Portal application"
   git push origin main
   ```

4. **Enable GitHub Pages:**
   - Go to your repository settings
   - Scroll to "Pages" section
   - Set source to "main" branch
   - Your app will be available at: `https://tristankgg.github.io/ref_portal/`

### Option 2: Local Testing

Simply open `index.html` in your web browser. The application will work fully offline with localStorage.

## Usage

### Login
- Email: `tristankgg@gmail.com`
- Password: `Password123!`

### Managing Referrals
1. Click "Add Referral" button
2. Fill in customer details
3. Submit to add to your referrals list
4. Click the edit icon (✏️) to modify
5. Click the trash icon (🗑️) to delete (soft delete)

### Profile
1. Navigate to Settings > My Profile
2. Edit any field
3. Click "Save" to store changes

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Data Storage

All data is stored in the browser's localStorage:
- `referrals` - Array of referral objects
- `currentUser` - Current user profile data

**Note:** Data is specific to each browser and device. Clearing browser cache will delete all data.

## Future Enhancements

- [ ] Google Sheets integration for cloud storage
- [ ] Multi-user support with authentication
- [ ] Analytics dashboard
- [ ] Rewards system
- [ ] Email notifications
- [ ] Export functionality

## Troubleshooting

**Data not saving?**
- Check if localStorage is enabled in your browser
- Try clearing cache and reloading

**Can't login?**
- Verify you're using the correct credentials: tristankgg@gmail.com / Password123!
- Make sure Caps Lock is off for the password

## License

MIT

## Support

For issues or feature requests, please open an issue in the GitHub repository.
