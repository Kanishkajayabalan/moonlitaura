# 🌙 Moonlit Aura - Deployment Guide

## Current Status ✅

- ✅ **GitHub Repository**: https://github.com/Kanishkajayabalan/moonlitaura
- ✅ **Features**: Login/Register with auto-redirect, Sidebar menu with 7 options
- 🚀 **Ready for Cloudflare Pages Deployment**

## Recent Updates (May 2026)

- ✨ Added login/register redirect functionality
- ✨ Created sidebar menu with:
  - 👤 Profile
  - 📦 Orders
  - 🚚 Track Order
  - 🏷️ Categories
  - ❤️ Wishlist
  - 📋 My Orders
  - 🚪 Logout
- ✨ Improved form validation and error messages
- ✨ Added username display in header and sidebar
- ✨ Enhanced animations and hover effects
- 🔧 Fixed Cloudflare Pages configuration (wrangler.json, _redirects, wrangler.toml)

---

## ⚠️ CRITICAL FIX for Build Errors

**If you see error**: "Executing user deploy command: npx wrangler deploy" or "No loader is configured"

**ROOT CAUSE**: Build command is set in Cloudflare Pages settings

**SOLUTION - IN CLOUDFLARE DASHBOARD**:

1. Go to https://dash.cloudflare.com
2. Click **Pages** → **moonlitaura**
3. Click **Settings** → **Builds & Deployments**
4. Find "Build command" field
5. **DELETE everything in it** (make it EMPTY)
6. Keep "Build output directory" = `.`
7. Click **Save**
8. Click **Deployments** tab
9. Click your failed deployment
10. Click **⋮** menu → **Retry deployment**
11. Wait 30-60 seconds for success ✅

**Why**: This is a static site (no build needed). Empty build command = Cloudflare serves files directly.

---

## 🚀 Deploy to Cloudflare Pages

### Option 1: Automatic Deployment (Recommended)

1. Go to [Cloudflare Dashboard](https://dash.cloudflare.com)
2. Navigate to **Pages** section
3. Click **Create a project** → **Connect to Git**
4. Select **GitHub** and authorize if needed
5. Choose repository: `Kanishkajayabalan/moonlitaura`
6. Configure build settings:
   - **Framework preset:** None
   - **Build command:** ⚠️ **LEAVE EMPTY** (no build needed for static site!)
   - **Build output directory:** `.` (root)
7. Click **Save and Deploy**

**Result**: Your site will be available at `https://moonlitaura.pages.dev`

### Option 2: Manual Deployment with Wrangler CLI

```bash
# Install Wrangler globally
npm install -g wrangler

# Authenticate with Cloudflare
wrangler login

# Deploy current project
wrangler pages deploy .

# Or deploy specific folder
wrangler pages deploy . --project-name=moonlitaura
```

---

## 📤 GitHub Push Instructions

To push new changes to GitHub:

```bash
# Navigate to project folder
cd "c:\Users\kanis\OneDrive\Desktop\website"

# Add all changes
git add .

# Commit with descriptive message
git commit -m "Update: Add new features"

# Push to main branch
git push origin main
```

**Note**: Cloudflare Pages will automatically redeploy when you push to the main branch.

---

## 🔧 Configure Custom Domain (Optional)

1. In Cloudflare Pages project settings
2. Go to **Custom domains**
3. Click **Add custom domain**
4. Enter your domain (e.g., `moonlitaura.com`)
5. Follow DNS configuration steps
6. Click **Activate domain**

---

## 📊 Environment Variables (Optional)

If you need environment variables:

```bash
# In Cloudflare Pages project settings:
# Go to Settings → Environment variables

# Add as needed:
SITE_URL=https://moonlitaura.pages.dev
API_BASE=https://api.example.com
```

---

## ✅ Deployment Checklist

- [x] GitHub repository created
- [x] Code pushed to GitHub (main branch)
- [x] Sidebar menu implemented with 7 options
- [x] Login/Register redirect working
- [x] Form validation improved
- [ ] Connect to Cloudflare Pages
- [ ] Configure custom domain (optional)
- [ ] Test all features on live site
- [ ] Set up monitoring/analytics

---

## 📞 Troubleshooting

### Build Failing on Cloudflare?
- Ensure build output directory is set to `.` (root)
- Check that all files are committed to Git
- Verify no build command is needed (static site)

### Images Not Loading?
- Check file paths are relative (e.g., `./images/pic.jpg`)
- Ensure all image files are included in Git repo

### Styling Not Applied?
- Clear browser cache
- Check CSS files are in root directory
- Verify CSS paths in HTML are correct

---

## 🔐 Security Notes

- Keep sensitive data in environment variables (not in code)
- Use HTTPS (Cloudflare provides this automatically)
- Store API keys in Cloudflare environment variables
- Never commit `.env` files or credentials

---

## 📈 Performance Tips

- Images are cached for 7 days
- CSS/JS cached for 1 day
- HTML cached for 1 hour
- Use image optimization tools for `.webp` format
- Minimize CSS and JavaScript for faster load

---

## 🆘 Support & Resources

- **Cloudflare Pages Docs**: https://developers.cloudflare.com/pages/
- **GitHub Pages Guide**: https://pages.github.com/
- **Git Documentation**: https://git-scm.com/doc
- **Contact**: support@moonlitaura.com

---

**Last Updated**: May 28, 2026  
**Repository**: https://github.com/Kanishkajayabalan/moonlitaura

## Step 4: Your Site is Live! 🚀

After deployment completes, your site will be available at:
```
https://moonlit-aura.pages.dev
```

You can also add a custom domain through Cloudflare settings.

## Files to Push to GitHub

Make sure these files are in your repository:
```
✅ index.html
✅ handmade silver necklace.webp
✅ lavender candle.jpg
✅ crochet handbag.jpg
✅ resin art frame.webp
✅ wood box.webp
✅ handmade diary.webp
✅ clay pot.jpg
✅ wall hanging.jpeg
✅ soap.jpg
✅ customized frame.avif
✅ README.md
✅ .gitignore
✅ DEPLOYMENT.md (this file)

❌ login.php (exclude)
❌ register.php (exclude)
❌ logout.php (exclude)
❌ config.php (exclude)
❌ setup.php (exclude)
❌ save_order.php (exclude)
❌ login page.html (exclude)
```

The `.gitignore` file will automatically exclude PHP files.

## Troubleshooting

**Site shows 404:**
- Ensure `index.html` is in the root directory
- Check that all image file names match exactly (case-sensitive)

**Images not loading:**
- Verify image files are in the same folder as `index.html`
- Check image file names in `index.html` vs actual file names

**Deployment failed:**
- Check Cloudflare Pages build logs for errors
- Ensure all required files are pushed to GitHub
