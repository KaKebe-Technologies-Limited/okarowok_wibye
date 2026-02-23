# Okarowok Wibye Blog System - Test Strategy

**Version:** 1.0  
**Date:** 2026-02-20  
**Author:** Test Strategy Document  
**Project:** Okarowok Wibye - Lango Cultural Heritage Blog  

---

## Table of Contents

1. [Overview](#overview)
2. [Unit Tests](#unit-tests)
3. [Integration Tests](#integration-tests)
4. [Edge Cases & Error Handling](#edge-cases--error-handling)
5. [Browser Compatibility Tests](#browser-compatibility-tests)
6. [Staging Environment Testing Guide](#staging-environment-testing-guide)
7. [Pre-Deployment Checklist](#pre-deployment-checklist)

---

## Overview

This document outlines the comprehensive test strategy for the Okarowok Wibye blog system, a PHP-powered flat-file blog using Markdown files for content management. The test strategy covers unit testing of individual components, integration testing of end-to-end workflows, edge case handling, browser compatibility, and staging deployment procedures.

### System Architecture Summary

- **Backend:** PHP 7.4+ with flat-file Markdown storage
- **Content Storage:** Markdown files in `content/posts/` directory
- **Caching:** File-based caching system
- **SEO:** JSON-LD structured data, Open Graph meta tags
- **URL Routing:** Apache `.htaccess` for clean URLs
- **Current Content:** 8 blog posts about Lango culture and heritage

---

## Unit Tests

### 1. Markdown Parsing Tests

#### 1.1 Front Matter Extraction

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| MT-001 | Parse valid front matter with title, date, tags, and image | All fields correctly extracted into associative array |
| MT-002 | Parse front matter with only required fields (title, date) | Required fields extracted; optional fields return null/default |
| MT-003 | Parse front matter with special characters in title | Special characters properly escaped and preserved |
| MT-004 | Parse front matter with multiple tags | All tags extracted as array |
| MT-005 | Parse empty front matter | Returns empty array or default values |
| MT-006 | Parse front matter with YAML boolean values | Boolean values correctly converted to PHP booleans |
| MT-007 | Parse front matter with date in various formats | Dates correctly parsed to Unix timestamp |

#### 1.2 Markdown Content Parsing

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| MT-101 | Parse markdown with headings (H1-H6) | All heading levels correctly converted to HTML |
| MT-102 | Parse markdown with bold and italic text | Text formatting properly converted |
| MT-103 | Parse markdown with links | Links converted to anchor tags with correct href |
| MT-104 | Parse markdown with images | Images converted to img tags with alt text |
| MT-105 | Parse markdown with unordered lists | Lists converted to `<ul>` and `<li>` elements |
| MT-106 | Parse markdown with ordered lists | Lists converted to `<ol>` and `<li>` elements |
| MT-107 | Parse markdown with code blocks | Code blocks wrapped in `<pre><code>` tags |
| MT-108 | Parse markdown with blockquotes | Blockquotes wrapped in `<blockquote>` tags |
| MT-109 | Parse markdown with horizontal rules | Horizontal rules converted to `<hr>` tags |
| MT-110 | Parse markdown with tables | Tables correctly converted to HTML table structure |

---

### 2. Blog Functions Tests

#### 2.1 getAllPosts() Function

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BF-001 | Call getAllPosts() with default parameters | Returns all posts sorted by date (newest first) |
| BF-002 | Call getAllPosts() with limit parameter | Returns specified number of posts |
| BF-003 | Call getAllPosts() with offset parameter | Returns posts starting from offset position |
| BF-004 | Call getAllPosts() with sort order ASC | Posts sorted oldest first |
| BF-005 | Call getAllPosts() with sort order DESC | Posts sorted newest first |
| BF-006 | Call getAllPosts() with no posts in directory | Returns empty array |
| BF-007 | Call getAllPosts() and verify post structure | Each post has: slug, title, date, excerpt, tags, image |

#### 2.2 getPostBySlug() Function

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BF-101 | Call getPostBySlug() with valid slug | Returns complete post data including full content |
| BF-102 | Call getPostBySlug() with invalid/non-existent slug | Returns null |
| BF-103 | Call getPostBySlug() with special characters in slug | Returns null (slug should be URL-safe) |
| BF-104 | Call getPostBySlug() with case-sensitive slug | Returns post (slugs are case-sensitive) |
| BF-105 | Verify post content includes rendered HTML | Full content is properly rendered |

#### 2.3 getPostsByTag() Function

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BF-201 | Call getPostsByTag() with valid tag | Returns all posts containing that tag |
| BF-202 | Call getPostsByTag() with case-insensitive tag | Returns matching posts regardless of case |
| BF-203 | Call getPostsByTag() with non-existent tag | Returns empty array |
| BF-204 | Call getPostsByTag() with multiple matching posts | Returns all matching posts sorted by date |
| BF-205 | Call getPostsByTag() with special characters in tag | Returns empty array or handles gracefully |
| BF-206 | Verify returned posts have correct tag | All returned posts contain the specified tag |

#### 2.4 Pagination Functions

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| PG-001 | Calculate pagination for 8 posts with 5 per page | 2 pages returned |
| PG-002 | Calculate pagination for 8 posts with 3 per page | 3 pages returned |
| PG-003 | Calculate pagination for exactly divisible posts | Correct page count with no remainder |
| PG-004 | Calculate pagination for single post | 1 page returned |
| PG-005 | Calculate pagination for zero posts | 0 pages returned |
| PG-006 | Generate pagination URLs for multiple pages | Correct URLs generated for all pages |
| PG-007 | Test pagination with custom URL base | URLs use specified base path |

---

### 3. Cache Functions Tests

#### 3.1 Cache Write Operations

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| CF-001 | Write cache with valid data | Cache file created successfully |
| CF-002 | Write cache with large data set | Cache file created without errors |
| CF-003 | Overwrite existing cache | Old cache replaced with new data |
| CF-004 | Write cache with null value | Cache file created with null content |
| CF-005 | Write cache with special characters | Data preserved correctly in cache |

#### 3.2 Cache Read Operations

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| CF-101 | Read existing cache | Returns cached data |
| CF-102 | Read non-existent cache | Returns null or default value |
| CF-103 | Read expired cache | Returns null (cache should be invalidated) |
| CF-104 | Read corrupted cache | Returns null; error logged |
| CF-105 | Verify cache data integrity | Retrieved data matches cached data |

#### 3.3 Cache Management

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| CF-201 | Clear all cache | All cache files removed |
| CF-201 | Clear specific cache | Only specified cache file removed |
| CF-203 | Cache invalidation on post update | Old cache cleared when post modified |
| CF-204 | Cache directory permissions | Cache directory is writable |

---

### 4. SEO Meta Tag Generation Tests

#### 4.1 Meta Tag Generation

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| SEO-001 | Generate meta tags for blog listing | Title, description, og:tags present |
| SEO-002 | Generate meta tags for single post | All post-specific meta tags generated |
| SEO-003 | Generate JSON-LD structured data | Valid JSON-LD schema markup present |
| SEO-004 | Generate Open Graph tags | og:title, og:description, og:image, og:url present |
| SEO-005 | Generate Twitter Card tags | twitter:card, twitter:title, twitter:description present |
| SEO-006 | Handle missing featured image | Default image used or og:image omitted |
| SEO-007 | Handle long titles | Titles truncated appropriately |
| SEO-008 | Handle special characters in meta | Characters properly escaped for HTML |

#### 4.2 Structured Data Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| SEO-101 | Generate BlogPosting schema | Valid schema.org BlogPosting JSON-LD |
| SEO-102 | Generate BreadcrumbList schema | Valid breadcrumb structured data |
| SEO-103 | Validate JSON-LD syntax | No JSON parsing errors |
| SEO-104 | Include author information | Author details in structured data |
| SEO-105 | Include datePublished/dateModified | Publication dates in schema |

---

### 5. URL Routing Tests (.htaccess)

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| RT-001 | Access /blog/ | Rewrites to blog/index.php |
| RT-002 | Access /blog/page/2/ | Rewrites to blog/index.php?page=2 |
| RT-003 | Access /blog/post/slug/ | Rewrites to blog/post.php?slug=slug |
| RT-004 | Access /blog/tag/tagname/ | Rewrites to blog/index.php?tag=tagname |
| RT-005 | Access non-existent path | Returns 404 page |
| RT-006 | Verify clean URLs work | URLs without .php extension function |
| RT-007 | Test URL case sensitivity | URLs work regardless of case |
| RT-008 | Test special characters in URLs | URLs with encoded characters work |

---

## Integration Tests

### 1. Blog Listing Page Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| IT-001 | Load blog listing page | Page loads without errors |
| IT-002 | Verify post count displays correctly | Correct number of posts shown |
| IT-003 | Verify post titles display | All post titles visible |
| IT-004 | Verify post excerpts display | Post excerpts/featured images visible |
| IT-005 | Verify post dates display | Publication dates shown correctly |
| IT-006 | Verify post tags display | Tags shown for each post |
| IT-007 | Verify post images display | Featured images load properly |
| IT-008 | Verify "Read More" links work | Links navigate to correct post pages |
| IT-009 | Verify page header/title | Page title is "Blog" or appropriate |
| IT-010 | Verify responsive layout | Page displays correctly on all screen sizes |

### 2. Individual Post Page Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| IT-101 | Load individual post page | Post content loads correctly |
| IT-102 | Verify post title displays | Title is prominently displayed |
| IT-103 | Verify post date displays | Date formatted correctly |
| IT-104 | Verify post author displays | Author name shown |
| IT-105 | Verify post content renders | Markdown content properly converted to HTML |
| IT-106 | Verify featured image displays | Image loads and displays correctly |
| IT-107 | Verify tags are clickable | Tag links navigate to filtered list |
| IT-108 | Verify social share buttons | Share buttons present and functional |
| IT-109 | Verify related posts section | Related posts displayed (if implemented) |
| IT-110 | Verify post navigation | Previous/Next post links work |

### 3. Pagination Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| IT-201 | Navigate to page 1 | First page of posts displays |
| IT-202 | Navigate to page 2 | Second page displays with correct posts |
| IT-203 | Click "Next" pagination link | Next page loads |
| IT-204 | Click "Previous" pagination link | Previous page loads |
| IT-205 | Navigate to last page | Last page displays correctly |
| IT-206 | Verify pagination info | "Showing X-Y of Z posts" text correct |
| IT-207 | Verify pagination URLs | URLs are clean and correct |
| IT-208 | Test pagination with URL parameter | ?page=2 parameter works |

### 4. Previous/Next Post Navigation Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| IT-301 | Verify "Next Post" link on oldest post | Next post link hidden or links to next oldest |
| IT-302 | Verify "Previous Post" link on newest post | Previous post link hidden or links to next newest |
| IT-303 | Click "Previous Post" link | Navigates to chronologically previous post |
| IT-304 | Click "Next Post" link | Navigates to chronologically next post |
| IT-305 | Verify navigation shows post titles | Post titles displayed in navigation |
| IT-306 | Verify navigation order is correct | Chronological order maintained |

### 5. Tag Filtering Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| IT-401 | Click tag link on post page | Filtered list of posts with tag displays |
| IT-402 | Verify filtered results | Only posts with selected tag shown |
| IT-403 | Verify tag appears in URL | URL contains tag parameter |
| IT-404 | Clear tag filter | All posts displayed again |
| IT-405 | Navigate to tag via URL | /blog/tag/tagname/ displays filtered posts |
| IT-406 | Test with multiple tags | Each tag filters correctly |
| IT-407 | Verify tag count display | Number of posts per tag shown |

### 6. Clean URL Routing Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| IT-501 | Access blog via /blog/ | Clean URL works |
| IT-502 | Access post via /blog/post/slug/ | Clean URL works |
| IT-503 | Access tag page via /blog/tag/name/ | Clean URL works |
| IT-504 | Verify no 404 on blog pages | All blog routes return 200 |
| IT-505 | Verify .php not visible in URLs | URLs are clean |

---

## Edge Cases & Error Handling

### 1. Invalid Routes and Parameters

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| EC-001 | Access blog with invalid slug | 404 page displays |
| EC-002 | Access blog with invalid page number (0) | Redirects to page 1 |
| EC-003 | Access blog with negative page number | Redirects to page 1 |
| EC-004 | Access blog with non-numeric page | 404 page displays |
| EC-005 | Access blog with page number beyond total | Last page displays |
| EC-006 | Access blog with invalid tag | Empty results or 404 |
| EC-007 | Access non-existent route | 404 page displays |

### 2. Empty Content Scenarios

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| EC-101 | Empty content/posts directory | "No posts available" message displays |
| EC-102 | No posts match tag filter | "No posts found" message displays |
| EC-103 | No posts on specific page | "No posts on this page" message or redirect |

### 3. Malformed Data Handling

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| EC-201 | Post with missing front matter | Default values used or post skipped |
| EC-202 | Post with malformed YAML front matter | Error logged; post handled gracefully |
| EC-203 | Post with missing title | Default title or post skipped |
| EC-204 | Post with missing date | Current date used or post skipped |
| EC-205 | Post with invalid date format | Error handled; date not displayed |
| EC-206 | Post with corrupted markdown | Partial content displayed |

### 4. Missing Assets

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| EC-301 | Featured image file missing | Placeholder image displays or image hidden |
| EC-302 | CSS file missing | Page loads without styles (fallback) |
| EC-303 | JS file missing | Core functionality works without JS |
| EC-304 | Font file missing | System fonts used as fallback |

### 5. Cache Corruption

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| EC-401 | Cache file corrupted | Fresh data generated; corrupted cache cleared |
| EC-402 | Cache file locked | Retry mechanism or fresh data generated |
| EC-403 | Cache directory not writable | Fresh data generated; warning logged |
| EC-404 | Cache expired | Fresh data generated automatically |

### 6. Performance Edge Cases

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| EC-501 | Very long post content | Page loads without timeout |
| EC-502 | Large number of posts (100+) | Pagination works efficiently |
| EC-503 | Large number of tags | Tags display without performance issues |
| EC-504 | Concurrent cache writes | No data corruption |

---

## Browser Compatibility Tests

### 1. Responsive Design Tests

#### Desktop (1920x1080 and above)

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BR-001 | Blog listing - desktop layout | 3-column grid displays correctly |
| BR-002 | Blog post - desktop layout | Content centered, sidebar visible if applicable |
| BR-003 | Navigation - desktop | All links accessible |
| BR-004 | Images - desktop | Images scale appropriately |

#### Tablet (768px - 1024px)

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BR-101 | Blog listing - tablet layout | 2-column grid displays correctly |
| BR-102 | Blog post - tablet layout | Content readable, no horizontal scroll |
| BR-103 | Navigation - tablet | Menu works correctly |
| BR-104 | Images - tablet | Images scale appropriately |
| BR-105 | Touch interactions - tablet | Tap targets adequately sized |

#### Mobile (320px - 480px)

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BR-201 | Blog listing - mobile layout | Single column displays correctly |
| BR-202 | Blog post - mobile layout | Content readable, no horizontal scroll |
| BR-203 | Navigation - mobile | Hamburger menu works |
| BR-204 | Images - mobile | Images scale to fit screen |
| BR-205 | Touch interactions - mobile | All interactive elements tappable |
| BR-206 | Pagination - mobile | Pagination controls usable |

### 2. Cross-Browser Tests

| Test Case ID | Browser | Description | Expected Result |
|--------------|---------|-------------|-----------------|
| BR-301 | Chrome (latest) | All features | Works as expected |
| BR-302 | Firefox (latest) | All features | Works as expected |
| BR-303 | Safari (latest) | All features | Works as expected |
| BR-304 | Edge (latest) | All features | Works as expected |
| BR-305 | Chrome (2 versions back) | Core features | Works as expected |
| BR-306 | Firefox (2 versions back) | Core features | Works as expected |

### 3. Accessibility Tests

| Test Case ID | Description | Expected Result |
|--------------|-------------|-----------------|
| BR-401 | Keyboard navigation | All elements accessible via keyboard |
| BR-402 | Screen reader compatibility | ARIA labels present, proper heading structure |
| BR-403 | Color contrast | WCAG AA compliant |
| BR-404 | Focus indicators | Visible focus indicators on interactive elements |
| BR-405 | Alt text on images | All images have appropriate alt text |
| BR-406 | Form labels | All form inputs have associated labels |

---

## Staging Environment Testing Guide

### 1. Setting Up Staging Environment on Hostinger

#### 1.1 Create Staging Site

1. **Log in to Hostinger hPanel**
   - Navigate to **Websites** → **Manage** for okarowok-wibye.com
   - Scroll to **Staging** section

2. **Deploy Staging**
   - Click **Create Staging**
   - Wait for deployment to complete (2-5 minutes)
   - Note the staging URL (e.g., `staging-username.hostingersite.com`)

3. **Access Staging via FTP/SFTP**
   - Go to **Files** → **File Manager** → **public_html_staging**
   - Or use FTP credentials from **Advanced** → **FTP Credentials**

#### 1.2 Upload Files to Staging

```bash
# Option 1: Via File Manager
1. Navigate to public_html_staging/
2. Upload all files from local /public_html or project root

# Option 2: Via FTP
ftp staging-username.hostingersite.com
cd public_html
put -r * 
```

#### 1.3 Configure Staging Settings

1. **PHP Version**
   - Go to **Websites** → **Manage** → **PHP** → **PHP Version**
   - Select **PHP 8.1** or recommended version

2. **PHP Extensions Required**
   - `mbstring` - Multibyte string support
   - `gd` or `imagick` - Image processing
   - `curl` - HTTP requests
   - `json` - JSON encoding

3. **Directory Structure Verification**
   ```
   public_html_staging/
   ├── .htaccess
   ├── index.html
   ├── blog.html
   ├── about.html
   ├── gallery.html
   ├── project.html
   ├── service.html
   ├── style.css
   ├── assets/
   │   ├── css/
   │   ├── js/
   │   ├── img/
   │   └── fonts/
   ├── blog/
   │   ├── index.php
   │   └── post.php
   ├── content/
   │   └── posts/
   ├── includes/
   │   ├── blog-functions.php
   │   ├── header.php
   │   └── footer.php
   └── cache/
   ```

---

### 2. Pre-Deployment Checklist

#### 2.1 Code Verification

| Item | Status | Notes |
|------|--------|-------|
| All PHP files have correct permissions (644) | [ ] | |
| Directory permissions set correctly (755) | [ ] | |
| Cache directory is writable (755) | [ ] | |
| .htaccess file exists and configured | [ ] | |
| All blog posts have valid front matter | [ ] | |
| No hardcoded paths to local system | [ ] | |
| Database credentials not hardcoded (if applicable) | [ ] | |
| Debug mode disabled | [ ] | |

#### 2.2 Content Verification

| Item | Status | Notes |
|------|--------|-------|
| All 8 blog posts uploaded | [ ] | |
| Featured images present | [ ] | |
| All internal links correct | [ ] | |
| No broken image references | [ ] | |

#### 2.3 Configuration Verification

| Item | Status | Notes |
|------|--------|-------|
| Site URL configured correctly | [ ] | |
| SEO settings updated for production | [ ] | |
| Sitemap.xml generated | [ ] | |
| robots.txt configured | [ ] | |
| Error pages configured | [ ] | |

---

### 3. Test Cases to Run on Staging

#### 3.1 Core Functionality Tests

Execute these tests on the staging URL before production deployment.

| Test ID | Test Description | Steps | Expected Result | Pass/Fail |
|---------|------------------|-------|-----------------|-----------|
| ST-001 | Blog listing loads | Visit staging URL/blog/ | Page loads with all posts | [ ] |
| ST-002 | Individual post loads | Click any post | Post content displays | [ ] |
| ST-003 | Pagination works | Navigate to page 2 | Different posts displayed | [ ] |
| ST-004 | Tag filtering works | Click a tag link | Filtered posts shown | [ ] |
| ST-005 | Clean URLs work | Enter /blog/post/slug/ | Post page loads | [ ] |
| ST-006 | 404 page works | Visit invalid URL | Custom 404 displays | [ ] |
| ST-007 | Cache works | Visit page twice | Second load faster | [ ] |
| ST-008 | Mobile responsive | Resize to mobile | Layout adapts | [ ] |

#### 3.2 SEO Verification Tests

| Test ID | Test Description | Steps | Expected Result | Pass/Fail |
|---------|------------------|-------|-----------------|-----------|
| ST-101 | Meta title correct | Inspect page source | Title tag present | [ ] |
| ST-102 | Meta description correct | Inspect page source | Description meta tag present | [ ] |
| ST-103 | Open Graph tags | Inspect page source | og:meta tags present | [ ] |
| ST-104 | JSON-LD structured data | Inspect page source | Valid JSON-LD script | [ ] |
| ST-105 | Canonical URLs | Inspect page source | Canonical tag present | [ ] |
| ST-106 | XML Sitemap | Visit /sitemap.xml | Sitemap XML loads | [ ] |

#### 3.3 Performance Tests

| Test ID | Test Description | Steps | Expected Result | Pass/Fail |
|---------|------------------|-------|-----------------|-----------|
| ST-201 | Page load time | Check in DevTools | < 3 seconds | [ ] |
| ST-202 | No render-blocking resources | Check DevTools | No critical errors | [ ] |
| ST-203 | Images optimized | Check DevTools | Images compressed | [ ] |
| ST-204 | Cache headers | Check response headers | Cache headers set | [ ] |

---

### 4. Simulating Production Conditions

#### 4.1 Enable Production Mode

1. **Disable Debug Mode**
   ```php
   // In includes/config.php or similar
   error_reporting(0);
   ini_set('display_errors', '0');
   ```

2. **Configure Caching**
   - Enable aggressive caching
   - Set appropriate cache expiration

3. **Optimize PHP**
   ```php
   // php.ini settings
   opcache.enable = 1
   opcache.memory_consumption = 128
   opcache.max_accelerated_files = 10000
   ```

#### 4.2 Test Under Load

1. **Using Browser DevTools**
   - Open Network tab
   - Disable cache
   - Reload page multiple times
   - Check for performance degradation

2. **Test Multiple Concurrent Users**
   - Use browser to simulate multiple tabs
   - Check for session conflicts
   - Verify cache coherence

3. **Test Network Conditions**
   - Throttle network in DevTools (Slow 3G)
   - Verify graceful degradation
   - Check loading states

---

### 5. Verification Steps for Live Deployment

#### 5.1 Pre-Launch Checklist

| Step | Action | Verification |
|------|--------|--------------|
| 1 | Backup current production site | Backup exists in Hostinger |
| 2 | Test on staging thoroughly | All tests pass |
| 3 | Update DNS if needed | DNS propagation complete |
| 4 | SSL certificate active | HTTPS works without errors |
| 5 | Remove staging files | Staging directory cleaned |
| 6 | Enable production PHP settings | Optimal performance |

#### 5.2 Post-Deployment Verification

Execute these commands/checks immediately after deployment:

```bash
# 1. Verify HTTPS works
curl -I https://okarowok-wibye.com

# 2. Verify blog listing loads
curl -I https://okarowok-wibye.com/blog/

# 3. Verify a post loads
curl -I https://okarowok-wibye.com/blog/post/oral-traditions-and-storytelling/

# 4. Verify sitemap
curl -I https://okarowok-wibye.com/sitemap.xml

# 5. Verify no errors in browser console
# Open browser DevTools → Console → Check for errors
```

#### 5.3 Final Browser Checks

| Check | Action | Expected Result |
|-------|--------|-----------------|
| Homepage | Visit okarowok-wibye.com | Homepage loads |
| Blog page | Visit okarowok-wibye.com/blog/ | Blog listing displays |
| Post page | Click on a post | Post content loads |
| Mobile | Check on phone | Responsive layout works |
| Contact form | Test contact form | Form submits (if applicable) |

---

### 6. Performance Testing

#### 6.1 Tools to Use

1. **Google PageSpeed Insights**
   - URL: https://pagespeed.web.dev/
   - Target: Score > 90

2. **GTmetrix**
   - URL: https://gtmetrix.com/
   - Target: Grade B or higher

3. **WebPageTest**
   - URL: https://www.webpagetest.org/
   - Target: First Contentful Paint < 2s

#### 6.2 Performance Benchmarks

| Metric | Target | Acceptable |
|--------|--------|------------|
| First Contentful Paint (FCP) | < 1.8s | < 3s |
| Largest Contentful Paint (LCP) | < 2.5s | < 4s |
| Time to First Byte (TTFB) | < 600ms | < 1s |
| Cumulative Layout Shift (CLS) | < 0.1 | < 0.25 |
| Total Blocking Time (TBT) | < 200ms | < 600ms |
| Page Size | < 3MB | < 5MB |
| Requests | < 50 | < 100 |

#### 6.3 Performance Optimization Checklist

| Item | Status | Action |
|------|--------|--------|
| Images optimized | [ ] | Use WebP, compress JPEGs |
| CSS minified | [ ] | Remove whitespace |
| JS minified | [ ] | Remove whitespace |
| Browser caching | [ ] | Set cache headers in .htaccess |
| Gzip compression | [ ] | Enable in .htaccess |
| Lazy loading | [ ] | Add loading="lazy" to images |
| CDN (optional) | [ ] | Consider Cloudflare |

---

## Summary

This test strategy document provides comprehensive coverage for the Okarowok Wibye blog system, including:

- **Unit Tests:** 50+ test cases covering markdown parsing, blog functions, caching, SEO, and URL routing
- **Integration Tests:** 50+ test cases covering end-to-end workflows for listing, posts, pagination, navigation, and tag filtering
- **Edge Cases:** 30+ test cases covering error handling, malformed data, missing assets, and performance edge cases
- **Browser Compatibility:** 20+ test cases covering responsive design, cross-browser testing, and accessibility
- **Staging Guide:** Complete deployment and testing procedures for Hostinger

### Test Execution Priority

1. **Critical (Must Pass Before Production)**
   - Blog listing page loads
   - Individual posts display
   - Clean URLs work
   - Pagination works
   - Tag filtering works
   - 404 page displays correctly

2. **High (Should Pass Before Production)**
   - All SEO meta tags present
   - JSON-LD structured data valid
   - Mobile responsive works
   - Cache functions correctly
   - Performance acceptable

3. **Medium (Can Pass After Production)**
   - All browser compatibility
   - All accessibility tests
   - Performance optimization

---

**Document Version:** 1.0  
**Last Updated:** 2026-02-20  
**Next Review:** After deployment completion
