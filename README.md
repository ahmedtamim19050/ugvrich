# UGV RICH

Public website and admin platform for **UGV RICH** — the Research, Innovation, Consultancy & Hub of the
University of Global Village.

Built with **Laravel 13**, **Filament 5**, **Tailwind CSS 4**, **Alpine.js** and **Vite 8**.

---

## Requirements

| Tool     | Version used |
| -------- | ------------ |
| PHP      | 8.3          |
| Composer | 2.9          |
| MySQL    | 8.4          |
| Node.js  | 24           |

## Setup

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# create the database, then:
php artisan migrate --seed
php artisan storage:link

npm run build      # or: npm run dev
```

The default `.env` expects a MySQL database named `ugvrich` on `127.0.0.1:3306` as `root` with no password
(the Laragon default). Adjust `DB_*` to suit.

### Admin login

The seeder creates one administrator:

| Field    | Value               |
| -------- | ------------------- |
| URL      | `/admin`            |
| Email    | `admin@ugv.edu.bd`  |
| Password | `password`          |

**Change this password before the site goes anywhere near production.**

Anyone with a row in the `users` table can reach the admin panel — see `User::canAccessPanel()` in
[app/Models/User.php](app/Models/User.php). There is no public registration route, so accounts are created
deliberately (seeder, `php artisan tinker`, or a Users resource if you add one). If self-registration is ever
introduced, tighten that method first.

---

## The public site

| Route                          | Page                                                                |
| ------------------------------ | ------------------------------------------------------------------- |
| `/`                            | Home — hero, core areas, services, about, why choose, process, projects, experts, testimonials, FAQ, news, CTA |
| `/about`                       | Institutional profile, vision, mission, who we serve, partners       |
| `/services`                    | The complete consultancy catalogue, grouped by area                  |
| `/services/{slug}`             | One consultancy area: services, experts, projects                    |
| `/research`                    | Research & innovation activities, publications, funded projects      |
| `/projects`, `/projects/{slug}`| Filterable project list and case-study detail                        |
| `/experts`, `/experts/{slug}`  | Searchable and filterable expert directory, plus profiles            |
| `/news`, `/news/{slug}`        | News and events, filterable by type                                  |
| `/contact`                     | Contact details and the consultancy request form                     |

### Look and feel

One light theme, no toggle. Surfaces are white and near-white; the palette lives in `@theme` in
[resources/css/app.css](resources/css/app.css) as a neutral `ink` ramp plus **one** accent, `brand`
(`#1b4dff`), always used solid. There are no gradients and no second accent — the contrast against all that
white is what carries it. The single saturated block on the page is the closing call to action.

On the home page the header is *attached to the hero*: it sits on top of the animated background, starts
transparent, and only takes on a white surface once you scroll past 90px. Everywhere else it is a normal
sticky bar. Header motion includes a slide-in on load with staggered nav items, an underline that grows from
the centre of each link, a dark utility bar that collapses on scroll, a bar that shrinks as it sticks, a
staggered mega-menu, and a scroll-progress bar along the bottom edge.

### The hero video

The home page opens on a looping video of engineers reviewing plans on site — the one dark block on an
otherwise light site, with a gradient scrim behind the copy and the header flipping to white text over it.

`public/media/hero.mp4` (720p, 2.4 MB) and its poster frame `hero-poster.jpg` are from **Pexels**
([video 8964792](https://www.pexels.com/video/8964792/)), used under the
[Pexels License](https://www.pexels.com/license/): free for commercial use, no attribution required, no
sign-up. Replace them from **Site Settings → Hero → Hero video / Hero poster frame**.

**Do not swap in footage taken from YouTube.** Downloading from YouTube breaches its Terms of Service and the
clips are copyrighted by their creators — putting one on a public university site invites an infringement
claim. If you want different footage, take it from a site that licenses it explicitly: Pexels, Pixabay,
Coverr or Mixkit all offer free commercial-use video.

The video is `muted`, `loop`, `playsinline` and `autoplay`, which is what browsers require for background
video to start on its own. Visitors whose system asks for reduced motion get the still poster instead.

---

## The admin panel

Filament 5 at `/admin`, organised into five navigation groups:

- **Requests** — Consultancy Requests (with a "new" badge in the sidebar), Subscribers
- **Services** — Consultancy Areas (with an inline Services relation manager), Services, Core Areas
- **People & Partners** — Experts, Partners, Testimonials
- **Content** — Projects, News & Events, Publications, FAQs
- **Site** — Site Settings, Statistics, Settings (raw key/value editor)

The dashboard shows a stats overview and the latest consultancy requests. Most tables are drag-and-drop
reorderable, and that order drives how things appear on the public site.

### Site Settings

`Site → Site Settings` is the page to use for everyday copy changes. It edits the `settings` table through a
tabbed form — Identity, Hero, About, Why choose us, Audience, Research, Process, Contact — with repeaters for
the list-shaped content (mission points, reasons to choose RICH, audiences, partnership types, process steps).

Saving flushes the settings cache via `App\Support\Site::flush()`, so changes go live immediately.

The hero headline is editable there too. The **Highlighted word** field controls which word in the headline
is picked out in the accent colour; the first case-insensitive match wins, and an empty value disables it.
The **Hero background** upload on the same tab replaces the bundled network GIF.

---

## Content model

| Model                | Purpose                                                          |
| -------------------- | ---------------------------------------------------------------- |
| `ServiceCategory`    | The five consultancy areas                                        |
| `Service`            | Individual services within an area                                |
| `CoreArea`           | Research / Innovation / Consultancy / Hub                         |
| `Expert`             | Faculty and professional associates (drives the directory)        |
| `Project`            | Featured projects and achievements                                |
| `Post`               | News items and events (`type` = `news` or `event`)                |
| `Publication`        | Research publications and funded projects                         |
| `Partner`            | Partner organisations (homepage marquee, About page)              |
| `Testimonial`        | Client feedback carousel                                          |
| `Faq`                | Accordion on the home and contact pages                           |
| `Stat`               | Animated figures in the hero and stat bands                       |
| `ConsultancyRequest` | Submissions from the public form, with status and internal notes  |
| `Subscriber`         | Newsletter sign-ups from the footer                               |
| `Setting`            | Key/value site copy, read through `App\Support\Site`              |

---

## Motion

Beyond the shared scroll-reveal, three effects carry the site's character. All three are disabled under
`prefers-reduced-motion`, verified by emulating that setting.

**Headline word reveal.** Words in `[data-split]` headings are wrapped at runtime and wipe up from a clipped
box on a 42ms stagger. The splitter in [resources/js/app.js](resources/js/app.js) walks **text nodes only**, so
inline markup — the accent `<span>` inside a headline — survives intact. Under reduced motion the split never
runs at all, so headings render as plain text.

**Cursor spotlight.** A soft brand-tinted glow tracks the pointer inside pillar and service cards. One
delegated `pointermove` listener for the whole page writes two custom properties (`--mx`, `--my`); the
gradient itself is pure CSS, so nothing is recalculated in JS per frame.

**Brand band.** A slow marquee of the four things RICH stands for, alternating solid and outlined type.
Decorative only, so it is `aria-hidden`.

The pillar cards additionally use a scroll-driven parallax via `animation-timeline: view()` — see the pillar
section of the stylesheet.

## Section photography

Two sets of bundled photos ship with the site, both from **Pexels** under the
[Pexels License](https://www.pexels.com/license/) (free commercial use, no attribution required). Both are
fallbacks — upload an image on the record and it takes over.

| Where | Files | Admin field |
| ----- | ----- | ----------- |
| Core area cards (home) | `public/media/pillars/{slug}.jpg` | Services → Core Areas → Cover photo |
| Consultancy area cards (home) | `public/media/services/{slug}.jpg` | Services → Consultancy Areas → Image |
| Vision card (home About) | `public/media/about/vision.jpg` | Site → Site Settings → About → Vision photo |

The consultancy cards use theirs as a **masked watermark**, not a cover: the photo is pinned to the
bottom-right, radially masked and held at 10% opacity, rising to 25% on hover. Dark text sits over these
cards, so the image is there for texture and a hint of subject — never as a competing picture.

## Pillar photography

The four core areas on the home page each carry a cover photo. Bundled defaults live in
`public/media/pillars/{slug}.jpg` — research, innovation, consultancy, hub — sourced from **Pexels** under the
[Pexels License](https://www.pexels.com/license/) (free commercial use, no attribution required).

They are a fallback, not a fixture: upload a photo on the Core Area record
(**Services → Core Areas → Cover photo**) and it replaces the bundled one. Real photographs of UGV labs,
workshops and meetings would be better than stock, and drop straight in.

The card is `.pillar-card` in [resources/css/app.css](resources/css/app.css) — the same soft-ring and
layered-shadow treatment as the partner chips, so the page keeps one card language.

## Partner marks

The "Trusted by…" section on the home page and the partner grid on About both render
[resources/views/components/partner-mark.blade.php](resources/views/components/partner-mark.blade.php) —
initials on a coloured tile, seeded from the organisation name so it is always the same mark. Tints come from
the brand ramp only, so the wall reads as one family rather than introducing a second accent.

This replaced one generic building icon repeated across every partner, which read as unfinished. Upload a real
logo on the Partner record and it takes over. Real partner logos are one of the strongest trust signals a
consultancy site has, so they are worth collecting.

The home-page section runs two rows drifting in opposite directions (one row if there are fewer than six
partners), paused on hover, with the partner count pulled from the Statistics record whose icon is `globe`.

## Cover art

Projects, news items and events show a cover image when one is uploaded. When one is **not**, they fall back
to [resources/views/components/cover-art.blade.php](resources/views/components/cover-art.blade.php) — inline
SVG art generated deterministically from the record slug.

Four motifs rotate (network, plotted columns, radial survey, contour lines), each seeded from a hash so a
record always gets the same one, adjacent cards differ, and there is no HTTP request or stored file. A single
warm accent marks one element in each.

This exists because empty placeholder boxes are what make an otherwise finished site read as unbuilt. It is a
fallback, not a substitute: **upload real photographs** — faculty headshots, project sites, event photos — and
they replace the generated art entirely. Nothing else you can do lifts the site as much.

## Uploads

Media is stored on the `public` disk (`FILESYSTEM_DISK=public`) and served through `public/storage`, so run
`php artisan storage:link` once per environment. Records without an image fall back to a deterministic
gradient placeholder rather than a broken image — see
[resources/views/components/media-frame.blade.php](resources/views/components/media-frame.blade.php).

Consultancy request attachments land in `storage/app/public/consultancy-requests` and are downloadable from
the admin record.

---

## Tests

```bash
php artisan test
```

A branded 404 lives at `resources/views/errors/404.blade.php`. Note that error views render outside the web
middleware group, so no error bag is shared and any `@error` directive would fatal — `AppServiceProvider`
shares an empty `ViewErrorBag` by default to cover that (the session middleware overwrites it on normal
requests).

19 feature tests cover every public route, expert search and filtering, 404s for unpublished and hidden
records, consultancy request validation, file upload and honeypot handling, newsletter idempotency, every
Filament page and edit screen, and the Site Settings save round-trip including the JSON repeaters.

---

## Notes on the seeded content

Everything from the project brief is seeded verbatim: the vision and mission statements, all four core areas
with their bullet lists, the five consultancy areas and every service under them, "Why Choose UGV RICH",
"Who We Serve", the research and innovation activities, and the partnership types.

Some supporting content was **written to make the site demonstrable and is placeholder**: the per-service
one-line descriptions, the ten expert profiles, six projects, testimonials, publications, partner names, news
and event items, and the statistics (120+ projects, 80+ experts, 45+ partners, 98% satisfaction). The contact
block uses the placeholders from the brief (`rich@ugv.edu.bd`, `+880 000 000000`). Replace all of it through
the admin panel with real records before launch.
