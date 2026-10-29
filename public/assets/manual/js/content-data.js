/* ==========================================================================
   SAVEST-HRIS User Manual — Content Data
   Structured content for every module and function in the system.
   Rendered by app.js. Screenshots live in assets/screenshots/.
   Each module and function carries its own `faq` array — questions are
   drawn only from that page's own content, never generic boilerplate.
   ========================================================================== */

const MANUAL_DATA = {
  appName: "SAVEST-HR",
  tagline: "User Manual",
  version: "1.5",

  // FAQ shown on the Home page — grounded only in what the Home page itself says.
  homeFaq: [
    {
      q: "How do I find a specific function in this manual?",
      a: "Browse by module using the cards below or the sidebar, or jump straight to a function with the “Search the whole manual” bar at the top of the page."
    },
    {
      q: "How many modules does SAVEST-HRIS have, and what do they cover?",
      a: "Seven: Dashboard, Employees, Employee Memos, Performance Evaluation, Attendance Management, Company Setup (Branches, Company Positions, Employment Status, Salary Grade), and System Settings — each card below shows how many functions it contains."
    },
    {
      q: "Can I save this manual for offline reading?",
      a: "Yes — open any page and click Export to PDF in the top-right corner to download a print-ready copy of that page."
    }
  ],

  modules: [
    // ============================================================ DASHBOARD
    {
      id: "dashboard",
      title: "Dashboard",
      icon: "home",
      intro: "Your landing page after signing in — a quick, real-time snapshot of the whole HRIS.",
      faq: [
        {
          q: "What's the difference between the Dashboard and the other modules?",
          a: "The Dashboard only summarizes data that already lives in Employees, Attendance Management, and System Settings — it's a snapshot, not an editing screen."
        },
        {
          q: "Do I need to sign in before seeing the Dashboard?",
          a: "Yes — Signing In is the required first step; you land on the Dashboard automatically right after a successful login."
        }
      ],
      functions: [
        {
          id: "signing-in",
          title: "Signing In",
          route: "/login",
          permission: "None — required before any other page can be accessed",
          screenshot: "login.png",
          description:
            "The Sign In page is the entry point to SAVEST-HRIS. Every user — regardless of role — starts here before reaching the Dashboard or any other module.",
          features: [
            "Username & Password sign-in — the standard login form.",
            "Show/hide password toggle (the eye icon) to confirm what you've typed.",
            "Login using Google — an alternate one-click sign-in for accounts linked to a Google identity.",
            "Inline error banner if the username or password is incorrect."
          ],
          steps: [
            "Open the SAVEST-HRIS URL in your browser — you land on the Sign In page automatically if you aren't already logged in.",
            "Enter your Username and Password.",
            "Click the eye icon next to the password field if you want to double-check what you typed.",
            "Click SIGN IN.",
            "Alternatively, click “Login using Google” to sign in with a linked Google account instead of a username/password.",
            "On success you are redirected straight to the Dashboard."
          ],
          tips: [
            "If sign-in fails, the page reloads with a red error banner stating whether the username or the password was incorrect.",
            "An account with no privileges assigned to its User Type will be signed out immediately as “Unauthorized Account” — contact an administrator to have a role assigned under System Settings → User Type & Permissions."
          ],
          faq: [
            {
              q: "What happens if I enter the wrong username or password?",
              a: "The page reloads with a red error banner that specifically tells you whether the username or the password was incorrect."
            },
            {
              q: "I signed in but got logged out immediately with “Unauthorized Account” — why?",
              a: "Your account's User Type has no privileges assigned yet. An administrator needs to grant it at least one privilege under System Settings → User Type & Permissions."
            },
            {
              q: "Can I sign in with Google instead of a username and password?",
              a: "Yes, via the “Login using Google” button — but only if your account was linked to a Google identity beforehand."
            }
          ]
        },
        {
          id: "overview",
          title: "Dashboard Overview",
          route: "/system/dashboard",
          permission: "None — visible to every signed-in user",
          screenshot: "dashboard.png",
          description:
            "The Dashboard is the first screen you see after signing in. It gives HR staff and administrators an at-a-glance view of the organization's headcount and the day's notable events, without needing to open any other module.",
          features: [
            "Active Roster Count — total number of employees whose Employment Status is marked “Current Employee” (limited to the branches assigned to your user type).",
            "Salary Grades Defined, Company Positions, Employment Statuses, and Biometric IDs Assigned — four quick counts pulled from Company Setup and the Biometric IDs module.",
            "Five coverage charts — Weekly Schedule Coverage, Position Coverage, Salary Grade Coverage, Employees by Employment Status, and Biometric ID Coverage — each a simple pie/donut of who has that item on file versus who doesn't (as of today for the schedule chart).",
            "View Employees button on each coverage chart — jumps straight to the matching list (List of Weekly Schedules, or List of Employees pre-set to that chart's filter) so you can act on the gap immediately.",
            "Today's Birthday List — current employees celebrating a birthday today.",
            "Today's Work Anniversary List — current employees whose hiring date falls on today AND who have completed at least one full year of service; the badge shows the year count (1st Anniversary and up — staff hired earlier this same year are not listed).",
            "System Logs feed — the 6 most recent actions taken in the system (logins, record updates, etc.), shown only to accounts with the “View System Logs” privilege."
          ],
          steps: [
            "Sign in with your username and password (or Google account) from the login page.",
            "You are redirected automatically to the Dashboard.",
            "Review the roster count and the Salary Grades / Positions / Employment Statuses / Biometric IDs cards for a quick numeric snapshot.",
            "Scan the five coverage charts for gaps — anyone missing a schedule, position, salary grade, or biometric ID — and click a chart's View Employees button to go fix it.",
            "Check today's birthday/anniversary cards for quick people-moments worth acknowledging.",
            "Check the System Logs panel to see the latest activity across the system (open System Settings → System Logs for the full list).",
            "Use the sidebar on the left to jump into any module."
          ],
          tips: [
            "The System Logs panel only appears if your account has the “View System Logs” permission.",
            "The Active Roster Count follows each employee's Employment Status — changing a status to or from a “Current Employee” status, or deleting an employee, updates the count automatically.",
            "The Weekly Schedule Coverage chart checks “as of today” — an employee's schedule can show as missing again once its End Date has passed, even if they had one before."
          ],
          faq: [
            {
              q: "Why can't I see the System Logs panel on my Dashboard?",
              a: "That panel only appears if your account has the “View System Logs” privilege. When shown, it lists the 6 most recent entries."
            },
            {
              q: "What do the five coverage charts actually measure?",
              a: "Each one is a simple with/without split: who has a weekly schedule in effect today, a company position, an active salary grade movement, an employment status (grouped), or at least one biometric ID — versus who doesn't."
            },
            {
              q: "Where does the “View Employees” button on a chart take me?",
              a: "To List of Weekly Schedules (for the schedule chart) or to List of Employees (for the other charts), where you can see exactly who is covered and who isn't, and act on it directly."
            },
            {
              q: "How often do the roster and coverage numbers update?",
              a: "Automatically, every time a record is added, changed, or deleted — there's no manual refresh needed."
            },
            {
              q: "Where do the Today's Birthday and Work Anniversary lists come from?",
              a: "They're pulled live from employee records for whoever has a birthday or hiring-date anniversary falling on the current day."
            },
            {
              q: "An employee was hired today — why aren't they on the Work Anniversary list?",
              a: "The list only counts completed years of service, so it starts at the 1st Anniversary. Someone hired earlier in the current year has a 0-year tenure and is intentionally left off."
            }
          ]
        }
      ]
    },

    // =============================================================EMPLOYEES
    {
      id: "employees",
      title: "Employees",
      icon: "users",
      intro: "The core HR module: onboarding, employee records, deleting employees, weekly work schedules (per employee and in bulk), biometric IDs, and the dated movement history for each employee's position, salary grade, and employment status. The master lists those movements draw from live under Company Setup.",
      faq: [
        {
          q: "Where do I start when onboarding a new hire?",
          a: "Employee Registration — it captures personal details, IDs, address, background, and the initial branch/position/employment-status assignment in one form."
        },
        {
          q: "Where do I find an employee's salary, position, or status history?",
          a: "Open List of Employees, click the ⋮ actions menu on their row, and choose Salary Grade Movement, Position Movement, or Employment Status Movement."
        },
        {
          q: "Which employees are shown in the Employees module?",
          a: "Only employees from the branches assigned to your user type. Deleted employees are removed from the working lists and can only be reviewed under Deleted Employees."
        }
      ],
      functions: [
        {
          id: "registration",
          title: "Employee Registration",
          route: "/employee/registration_form",
          permission: "ADD NEW EMPLOYEE",
          screenshot: "employee-registration.png",
          description:
            "A comprehensive onboarding form used to create a brand-new employee record. It captures personal details, government IDs, address, family background, educational and work history, character references, and initial employment assignment (branch, position, date hired) in one submission.",
          features: [
            "1 · Personal Data — name (last, first, middle, suffix), gender, date/place of birth, civil status, religion, citizenship, plus sub-sections for Physical Information (height/weight), Contact Information (email, telephone, cellphone), Emergency Contact (contact person, contact no., address), Government IDs (SSS, TIN, PhilHealth, PAG-IBIG, National ID, Driver's License), and Home Address (province, city/municipality, barangay, street).",
            "2 · Family Background — spouse information (name, occupation, employer), father's and mother's name, and children (separate the names with a comma).",
            "3 · Employment Details — Employee ID (optional — generated automatically when left blank), Date Employed, Account Status, Branch, Position, and Employment Status.",
            "4 · Educational Background — College and Graduate Studies (school, course/major, year graduated); each supports multiple entries.",
            "5 · Employment Record — previous company, position, salary (PHP), date started and date ended; supports multiple entries.",
            "6 · Character References — name, company, position, and contact number; use “Add Reference” for more than one."
          ],
          steps: [
            "Go to Employees → Employee Registration in the sidebar.",
            "Complete each numbered section of the form, starting with Personal Data.",
            "Add extra Educational Background, Employment Record, and Character Reference rows as needed with the add controls in those sections.",
            "Fill in Employment Details — the Branch, Position, and Employment Status chosen here are the employee's starting assignment. Leave Employee ID blank to have the system generate one (year of the Date Employed followed by a running number, e.g. 2026-014), or type your own.",
            "Review all required fields (marked with a red asterisk) and click Submit Registration to create the record.",
            "The new employee now appears in List of Employees and becomes selectable in schedules, movements, and other modules."
          ],
          tips: [
            "Required fields are marked with a red asterisk — the form will not submit until they're filled in, and any problems are listed in a red “Please fix the following errors” banner at the top.",
            "Have the employee's 201-file documents on hand before starting; several sections mirror a standard PDS (Personal Data Sheet).",
            "Employee ID must be unique — the system rejects a number that another employee already has.",
            "Submitting the form also creates the employee's first Position Movement and Employment Status Movement (effective on the Date Employed) and a login account whose username is the Employee ID. The account cannot be used until an administrator assigns it a User Type under System Settings → User Accounts."
          ],
          faq: [
            {
              q: "What happens if I skip a required field?",
              a: "The form will not submit — required fields are marked with a red asterisk and must be filled in first."
            },
            {
              q: "Can I add more than one school or previous employer?",
              a: "Yes — the Educational Background and Work Experience sections both support multiple entries using their “add row” controls."
            },
            {
              q: "What happens right after I submit the registration form?",
              a: "The new employee immediately appears in List of Employees and becomes selectable in schedules, movements, and other modules."
            },
            {
              q: "Is Employee ID required?",
              a: "No — leave it blank and the system generates one (year of the Date Employed plus a running number). If you type one, it must be unique. You can change it later from List of Employees → ⋮ → Update Employee ID."
            },
            {
              q: "Does registering an employee also create a login?",
              a: "Yes — a user account is created with the Employee ID as its username. It cannot sign in successfully until an administrator gives it a User Type under System Settings → User Accounts."
            },
            {
              q: "Do I need to add the employee's first position and status movement myself?",
              a: "No — the initial Position Movement and Employment Status Movement are created automatically, effective on the Date Employed. Use the movement pages only for later changes."
            }
          ]
        },
        {
          id: "employee-list",
          title: "List of Employees",
          route: "/employee/employee_list",
          permission: "VIEW LIST OF EMPLOYEES",
          screenshot: "employee-list.png",
          modalScreenshot: "employee-list-actions-menu.png",
          modalLabel: "Row actions menu (⋮)",
          description:
            "The master directory of all registered, non-deleted employees in your assigned branches, with filters and a quick per-row action menu that opens into every other employee-centric function in the system.",
          features: [
            "Filter the list by Branch, Position, Employment Status, Date Employed, and Salary Grade (All / With Salary Grade / Without Salary Grade Yet) — the list refreshes as soon as you change a filter.",
            "Quick filter search box, pagination, and an adjustable page size (10, 20 or 50 rows).",
            "Table columns — Employee (name and Employee ID), Branch, Position, Employment Status, Date Employed, Salary Grade (a “With Salary Grade” or “Without Salary Grade Yet” badge), and Actions.",
            "Per-employee actions menu (⋮): View Employee Info, Update Employee Info, Weekly Schedules, Salary Grade Movement, Position Movement, Update Profile Picture, Update Employee ID, Employment Status Movement, and Delete Employee.",
            "Deleted Employees button (top right) — opens the read-only list of deleted employees; shown only with the “Delete Employee” privilege."
          ],
          steps: [
            "Go to Employees → List of Employees.",
            "Use the Branch, Position, Employment Status, Date Employed, or Salary Grade filters to narrow the list, or type in Quick Filter to search by name.",
            "Click the ⋮ actions icon on an employee's row to reveal the available actions for that employee.",
            "Choose View Employee Info to open the full read-only record (in a new tab), or Update Employee Info to open the editable form.",
            "Choose Weekly Schedules, Salary Grade Movement, Position Movement, or Employment Status Movement to manage that employee's history in the respective sub-function.",
            "Choose Update Profile Picture to upload a new photo, or Update Employee ID to change the employee's ID number, then click Update in the dialog.",
            "Choose Delete Employee (red, at the bottom of the menu) and confirm to remove the employee from the list — see Deleted Employees."
          ],
          tips: [
            "This page is the hub of the Employees module — most other employee functions are reached from its row actions rather than the sidebar.",
            "Which items appear in the ⋮ menu depends on your privileges: Update Employee Info needs “Update Employee Info”, Employment Status Movement needs “Update Employment Status”, Delete Employee needs “Delete Employee”, and the schedule, salary-grade, position, profile-picture and Employee ID items are shown to accounts that can view employee weekly schedules.",
            "Update Employee ID rejects an ID that is already used by another employee."
          ],
          faq: [
            {
              q: "How do I open an employee's salary or position history from here?",
              a: "Click the ⋮ actions icon on their row and choose Salary Grade Movement or Position Movement."
            },
            {
              q: "Can I narrow the list down before searching?",
              a: "Yes — use the Branch, Position, Employment Status, Date Employed, or Salary Grade filters, or type a name into the Quick Filter box."
            },
            {
              q: "What does the Salary Grade column/filter mean?",
              a: "It shows whether the employee has at least one salary grade movement on file. Filter by “Without Salary Grade Yet” to find employees who still need one assigned via Salary Grade Movement."
            },
            {
              q: "Is this the only place to reach an employee's Weekly Schedule or Edit form?",
              a: "It's the main hub for it — this page is where most other employee functions are reached from, via each row's ⋮ actions menu."
            },
            {
              q: "How do I change an employee's photo or Employee ID?",
              a: "Use Update Profile Picture or Update Employee ID in the row's ⋮ menu. Neither can be changed on the Edit form, where the Employee ID is locked."
            },
            {
              q: "What happens when I delete an employee?",
              a: "Deleting is only possible with the “Delete Employee” privilege. The employee disappears from List of Employees, weekly schedule lists and the roster counts, their login account is switched to Inactive, and the deletion is written to System Logs. The record itself is kept and remains visible under Deleted Employees."
            },
            {
              q: "Why can't I see the Delete Employee option or the Deleted Employees button?",
              a: "Both need the “Delete Employee” privilege, which an administrator can grant under System Settings → User Type & Permissions."
            }
          ]
        },
        {
          id: "deleted-employees",
          title: "Deleted Employees",
          route: "/employee/deleted",
          permission: "DELETE EMPLOYEE",
          screenshot: "deleted-employees.png",
          description:
            "A read-only list of employees that were deleted from List of Employees. Their records are preserved for reference, and their user accounts have been disabled.",
          features: [
            "Table columns — Employee (name and Employee ID, with a greyed-out photo), Branch, Position, Employment Status, Date Employed, and Actions.",
            "Quick filter search box, page size and pagination, like the other lists.",
            "View (eye) action — opens the deleted employee's read-only profile in a new tab.",
            "Limited to the branches assigned to your user type.",
            "Back to List of Employees button in the top-right corner.",
            "Read-only — there is no restore or edit action on this page."
          ],
          steps: [
            "Go to Employees → List of Employees and click the Deleted Employees button in the top-right corner.",
            "Browse or use Quick filter to find the deleted employee.",
            "Click the eye icon on a row to open that employee's profile.",
            "Click Back to List of Employees to return to the active list."
          ],
          tips: [
            "This page is only reachable with the “Delete Employee” privilege; anyone else who opens its address is refused access.",
            "The deletion itself is recorded in System Logs as “Delete Employee”. Open its View (eye) icon to see who was deleted and whether their account was disabled."
          ],
          faq: [
            {
              q: "Can I restore a deleted employee from this page?",
              a: "No — the page is read-only. It only lets you look up who was deleted."
            },
            {
              q: "Why can't a deleted employee sign in any more?",
              a: "Deleting an employee also sets their user account to Inactive, and inactive accounts cannot sign in."
            },
            {
              q: "Do deleted employees still count on the Dashboard or appear in schedule lists?",
              a: "No — they are left out of the Dashboard counts, List of Employees, List of Weekly Schedules and Bulk Scheduling."
            }
          ]
        },
        {
          id: "employee-profile",
          title: "Employee Profile (View)",
          route: "/employee/employee_profile/{employee_id}",
          permission: "VIEW LIST OF EMPLOYEES",
          screenshot: "employee-profile.png",
          description:
            "A read-only, full-detail view of a single employee's 201-file, opened from the List of Employees row action menu. It is organised in the same sections as the registration form: Personal Data, Family Background, Employment Details, Educational Background, Employment Record, and Character References.",
          steps: [
            "From List of Employees, click the ⋮ menu on the desired employee and select View Employee Info (it opens in a new tab).",
            "Review the employee's complete record across all sections.",
            "Click Edit Employee at the bottom of the page to jump to the editable form, or Back to List to return to List of Employees."
          ],
          tips: [
            "The employee's photo is shown at the top of the profile. To change it, use Update Profile Picture in the List of Employees ⋮ menu."
          ],
          faq: [
            {
              q: "Can I edit the employee's information from this Profile view?",
              a: "No — this page is read-only. Click Edit Employee at the bottom, or use Update Employee Info from the List of Employees ⋮ menu."
            },
            {
              q: "Can I update the employee's photo from this page?",
              a: "No — use Update Profile Picture in the List of Employees ⋮ menu."
            }
          ]
        },
        {
          id: "employee-edit",
          title: "Edit Employee",
          route: "/employee/employee_edit/{employee_id}",
          permission: "UPDATE EMPLOYEE INFO",
          screenshot: "employee-edit.png",
          description:
            "An editable version of the employee record (titled “Employee Information”), mirroring the Employee Registration form, used to correct or update any previously saved information.",
          steps: [
            "From List of Employees, click the ⋮ menu on the desired employee and select Update Employee Info.",
            "Update any of the personal, employment details (Date Employed, Account Status and Branch), family, education, employment record, or character reference fields.",
            "Click Update Record to save the changes, or Cancel to go back to the list without saving.",
            "Changes are reflected immediately in the List of Employees and the employee's Profile view."
          ],
          tips: [
            "The Employee ID is locked on this form — change it with Update Employee ID in the List of Employees ⋮ menu.",
            "Employment Status and Position are not editable here; use Employment Status Movement and Position Movement to log a formal change with an effective date."
          ],
          faq: [
            {
              q: "Does changing Employment Status here create a dated history record?",
              a: "The form doesn't offer an Employment Status (or Position) field — use Employment Status Movement or Position Movement to log a formal, dated change."
            },
            {
              q: "Why can't I type in the Employee ID field?",
              a: "It is locked on this form. Use Update Employee ID in the List of Employees ⋮ menu to change it."
            },
            {
              q: "Where do I see my changes after saving?",
              a: "Immediately in both List of Employees and that employee's Profile view."
            }
          ]
        },
        {
          id: "birthdays",
          title: "Upcoming Birthdays",
          route: "/employee/upcoming_birthdays",
          permission: "VIEW UPCOMING BIRTHDAYS",
          screenshot: "upcoming-birthdays.png",
          description:
            "Shows employees' birthdays around today, so HR can plan greetings, recognition, or celebrations in advance. The page is split into three sections: Today's Birthday, Upcoming Birthdays — Next 14 Days, and Previous Birthdays — Past 7 Days. Each card shows the employee and a badge with the age they are turning (e.g. 30th Birthday).",
          steps: [
            "Go to Employees → Upcoming Birthdays.",
            "Check Today's Birthday first, then the Upcoming Birthdays — Next 14 Days section for greetings to prepare.",
            "Use Previous Birthdays — Past 7 Days to catch any birthday you missed.",
            "Cross-reference with the employee's Branch if you want to coordinate a per-branch celebration."
          ],
          faq: [
            {
              q: "How far ahead and behind does the page look?",
              a: "It lists today's birthdays, those in the next 14 days (starting tomorrow), and those from the past 7 days."
            },
            {
              q: "How is each section ordered?",
              a: "Upcoming birthdays start with the nearest date; previous birthdays start with the most recent one."
            },
            {
              q: "Are employees from every branch shown?",
              a: "Only employees from the branches assigned to your user type, excluding deleted and resigned employees."
            }
          ]
        },
        {
          id: "anniversaries",
          title: "Upcoming Work Anniversaries",
          route: "/employee/work_anniversarries",
          permission: "VIEW UPCOMING WORK ANNIVERSARIES",
          screenshot: "work-anniversaries.png",
          description:
            "Lists employees who are about to reach (or have just passed) a work anniversary milestone (e.g. 1 year, 5 years of service), based on their Date Employed. The page is split into Today's Work Anniversary, Upcoming Work Anniversaries — Next 14 Days, and Previous Work Anniversaries — Past 7 Days, and each card shows the year count (e.g. 5th Work Anniversary).",
          steps: [
            "Go to Employees → Upcoming Work Anniversaries.",
            "Review today's anniversaries, then the next 14 days, then the past 7 days, noting how many years of service each employee completes.",
            "Use this list for service awards, loyalty bonuses, or regularization checks."
          ],
          faq: [
            {
              q: "What is this list calculated from?",
              a: "Each employee's Date Employed field, compared against today's date to find service milestones within the last 7 or next 14 days."
            },
            {
              q: "What is this page typically used for?",
              a: "Planning service awards, loyalty bonuses, or regularization checks ahead of the milestone date."
            }
          ]
        },
        {
          id: "weekly-schedules-list",
          title: "List of Weekly Schedules",
          route: "/ews/load_wsl",
          permission: "VIEW WEEKLY SCHEDULES",
          screenshot: "weekly-schedules-list.png",
          description:
            "Overview of every current employee's weekly work schedule, day by day, with Branch and Schedule filters and a Bulk Scheduling button for giving the same schedule to many employees at once. Every current employee is listed — including those who don't have a schedule in effect today.",
          features: [
            "One row per employee, sorted alphabetically by name (last name, then first name).",
            "Only current employees are listed: deleted employees and employees whose Employment Status is not marked “Current Employee” are left out.",
            "Employees with no schedule in effect today are still listed, with “—” in the day columns and a “No schedule yet” badge in place of the End Date.",
            "Columns — Actions, Employee (name and Employee ID), Effective date, End Date (“Present” when the schedule is still running), and a Work and Break column for each day, Monday to Sunday. Rest days show “Day-off”.",
            "Branch filter — limits the list to one branch (default: All Branches); the list is always limited to the branches assigned to your user type.",
            "Schedule filter — All / With Schedule / Without Schedule, based on whether a schedule is in effect as of today's date.",
            "Row actions — View Schedule (the employee's full schedule history), Edit Schedule (the employee's current schedule), or Add Schedule (the green + shown for employees with no schedule in effect today).",
            "Bulk Scheduling button (top right) — opens the Bulk Scheduling page."
          ],
          steps: [
            "Go to Employees → List of Weekly Schedules.",
            "Optionally pick a Branch and/or a Schedule status to narrow the list, or use the Quick filter box to find an employee by name.",
            "Review each employee's current work and break times for every day of the week.",
            "Click the eye icon to view an employee's schedule history, the pencil icon to edit their current schedule, or the green + icon to add a schedule for an employee who doesn't have one in effect today.",
            "Click Bulk Scheduling to assign one schedule to several employees at once."
          ],
          tips: [
            "Only the schedule record in effect today is shown here. Older or future-dated records live in that employee's schedule history (eye icon).",
            "If an employee you expect is missing, check that their Employment Status is marked “Current Employee” and that they have not been deleted."
          ],
          faq: [
            {
              q: "How do I update schedules for many employees at once?",
              a: "Click Bulk Scheduling at the top right of this page and select the employees to include."
            },
            {
              q: "Why is an employee showing “No schedule yet”?",
              a: "They have no weekly schedule record in effect as of today. Click the green + icon on their row to create one."
            },
            {
              q: "What does the Schedule filter check against?",
              a: "Today's date — an employee counts as “With Schedule” only if they have a record whose Effective Date has started and whose End Date hasn't passed (or is left open). A schedule that already ended shows as “Without Schedule” again, even if one existed before."
            },
            {
              q: "Why is a particular employee not on this list?",
              a: "Deleted employees, and employees whose Employment Status is not marked “Current Employee” (for example Resigned), are hidden. Employees outside the branches assigned to your user type are hidden too."
            },
            {
              q: "In what order is the list sorted?",
              a: "Alphabetically by employee name — last name first, then first name."
            },
            {
              q: "How do I see just one employee's full schedule history?",
              a: "Click the eye icon on their row, or open it from that employee's ⋮ menu in List of Employees."
            }
          ]
        },
        {
          id: "bulk-scheduling",
          title: "Bulk Scheduling",
          route: "/ews/load_bulk_update",
          permission: "VIEW WEEKLY SCHEDULES",
          screenshot: "bulk-scheduling.png",
          description:
            "Assigns a single weekly schedule to many employees in one save. Pick the employees, set the period, build the week, and the system creates a new schedule record for each selected employee.",
          fields: [
            { name: "Select Employees", description: "One or more current employees (deleted employees and non-current statuses are not offered). Use Select all / Clear all, or the search box to find people by ID or name. Required." },
            { name: "Effective Date", description: "The date the schedule starts. Required." },
            { name: "End Date", description: "The date the schedule ends. Leave blank for “Present” (no end)." },
            { name: "Work Hours (Start → End)", description: "The daily start and end time, entered in 24-hour HH:MM (defaults 08:00 → 17:00). Required unless the day is switched to Day Off." },
            { name: "Break (Start → End)", description: "Optional break times for the day (defaults 12:00 → 13:00)." },
            { name: "Day Off", description: "Toggle on for a rest day — the time fields are disabled and the day is stored as a day off." }
          ],
          steps: [
            "Go to Employees → List of Weekly Schedules and click Bulk Scheduling.",
            "Click the Select Employees box, tick each employee (or use Select all), and close the list — the count shows how many are selected.",
            "Enter the Effective Date, and an End Date if the schedule is not open-ended.",
            "In the Weekly Schedule Builder, set the work hours and optional break for each day, or switch a day to Day Off. The work/off counters at the top of the builder update as you go.",
            "Click Save Schedule. Each selected employee gets a new schedule record and you are returned to the page with a success message."
          ],
          tips: [
            "The save is all-or-nothing: if any selected employee is blocked, nothing is saved and the error names that employee.",
            "An employee is blocked when they still have an open (no End Date) schedule — set an end date on it first — or when the new period overlaps one of their existing schedules."
          ],
          faq: [
            {
              q: "Why did Bulk Scheduling refuse to save?",
              a: "One of the selected employees still has an active schedule with no End Date, or the new period overlaps an existing one. The red message names the employee; fix that record (or deselect the employee) and save again."
            },
            {
              q: "Does Bulk Scheduling overwrite existing schedules?",
              a: "No — it adds a new dated record for each employee. Existing records are only affected by their own edit and delete actions."
            },
            {
              q: "Who appears in the employee list?",
              a: "Current employees only — deleted employees and employees whose Employment Status is not marked “Current Employee” are excluded."
            }
          ]
        },
        {
          id: "employee-weekly-schedule",
          title: "Employee Weekly Schedule",
          route: "/ws/load_schedules/{employee_id}",
          permission: "VIEW EMPLOYEE WEEKLY SCHEDULES · UPDATE EMPLOYEE WEEKLY SCHEDULES",
          screenshot: "employee-weekly-schedule.png",
          modalScreenshot: "weekly-schedule-set-new.png",
          modalLabel: "Set New Weekly Schedule page",
          description:
            "The dated weekly-schedule history of one employee (“Schedule History”), with the work and break times for each day of the week. From here you can set a new schedule, edit the current one, or delete the latest one.",
          features: [
            "Employee strip — the employee's name, Employee ID and branch.",
            "Schedule History table — one row per schedule record, newest first, with Effective Date, End Date (“Present” while open), and Work / Break times for Monday to Sunday. The current record carries a green “Current” badge and rest days show “Day-off”.",
            "Set New Schedule button — opens the Set New Weekly Schedule page.",
            "Edit (pencil) — available on the current record only; the icon is greyed out on older records.",
            "Delete (trash) — available only on the current record and only when it is not the employee's first schedule; otherwise it is greyed out."
          ],
          fields: [
            { name: "Effective Date", description: "The date the schedule starts. Required." },
            { name: "End Date", description: "The date the schedule ends. Leave blank for “Present” (still active)." },
            { name: "Work Hours (Start → End)", description: "Start and end time for each day in 24-hour HH:MM (defaults 08:00 → 17:00). Required unless the day is a Day Off." },
            { name: "Break (Start → End)", description: "Optional break times for each day." },
            { name: "Day Off", description: "Switch on for a rest day; that day's time fields are disabled." }
          ],
          steps: [
            "From List of Employees, open the ⋮ menu on an employee and choose Weekly Schedules (or click the eye icon in List of Weekly Schedules).",
            "Review the Schedule History to see which schedule is current and how earlier ones ran.",
            "To add one, click Set New Schedule, enter the Effective Date (and End Date if known), set each day's work hours and optional break — or switch it to Day Off — then click Save Schedule.",
            "To change the current schedule, click the pencil icon, adjust the dates or times on the Update Weekly Schedule page, and click Save Changes.",
            "To remove the latest schedule, click the trash icon and confirm."
          ],
          tips: [
            "A new schedule cannot be added while the employee still has an open schedule with no End Date. Edit the current record and give it an End Date first.",
            "The date range of a new or edited schedule may not overlap another record of the same employee — the page shows a red message if it does.",
            "Deleting the latest schedule re-opens the one before it (its End Date is cleared) so the employee is never left without a current schedule.",
            "The work/off counters at the top of the builder show how many working days and days off the week has."
          ],
          faq: [
            {
              q: "Why can't I click Edit or Delete on an older schedule?",
              a: "Only the current (latest) record can be edited, and only the current record can be deleted — and never the employee's first one. Older records are kept as history."
            },
            {
              q: "Why does the system say I must set an end date first?",
              a: "An employee can have only one open schedule. Edit the current one, give it an End Date, then add the new schedule."
            },
            {
              q: "Why was my schedule rejected as overlapping?",
              a: "Its Effective/End Date range overlaps another schedule record of the same employee. Adjust the dates so the periods do not overlap."
            },
            {
              q: "How do I reach one employee's schedule page?",
              a: "From List of Employees, open the ⋮ menu on that employee and choose Weekly Schedules, or click the eye icon on their row in List of Weekly Schedules."
            }
          ]
        },
        {
          id: "salary-grade-movement",
          title: "Salary Grade Movement",
          route: "/salary-grade-movement/{employee_id}",
          permission: "VIEW EMPLOYEE WEEKLY SCHEDULES",
          screenshot: "salary-grade-movement.png",
          description:
            "A dated history of an employee's salary grade assignments — records when a grade/step took effect and, if applicable, when it ended, so pay changes over time stay auditable. The header shows the employee's number, name and branch; the List of Records table shows Grade Level, Step, Basic Salary, Effective Date, End Date (“Present” while open), Remarks, and Actions.",
          steps: [
            "From List of Employees, open the ⋮ menu on an employee and choose Salary Grade Movement.",
            "Click “+ Add Salary Grade Movement” to record a new assignment.",
            "Select the Salary Grade, set the Effective Date (and End Date if it's already superseded), optionally add Remarks, then click Save.",
            "To correct the current record, click its pencil icon, change the fields, and click Update.",
            "To remove the latest record, click its trash icon and confirm with Delete."
          ],
          tips: [
            "A new movement cannot be added while the employee still has an open record with no End Date — edit the current record and give it an End Date first.",
            "The date range may not overlap another salary grade movement of the same employee.",
            "Only the current (latest) record can be edited, and only the current record can be deleted — never the employee's first one. Deleting the latest record re-opens the one before it."
          ],
          faq: [
            {
              q: "When should I set an End Date on a movement entry?",
              a: "Once that salary grade assignment has been superseded by a newer one. You must end the current record before a new one can be added."
            },
            {
              q: "Can I fix a mistake in a movement I already logged?",
              a: "Only in the current record — use its edit icon. Older records are locked as history, and only the latest record can be deleted (not the first one)."
            },
            {
              q: "Why can't I pick a salary grade that is missing from the list?",
              a: "The dropdown offers the grades defined under Company Setup → Salary Grade. Add the missing grade there first."
            },
            {
              q: "If someone later changes the amount of a Salary Grade in Company Setup, will it change what's already saved here?",
              a: "No. When you assign a salary grade to an employee, the system saves a copy of that grade's amount at that exact time. If the Salary Grade's amount is changed later, the employee's saved record here keeps showing the amount that was true when it was recorded — it does not get rewritten. This keeps payroll history accurate, so past records always reflect what the employee was actually paid back then."
            },
            {
              q: "How do I update an employee's current salary amount?",
              a: "You may edit the employee's existing Salary Grade Movement record and change it to the new salary grade — this replaces the saved amount on that record. Or you may add a new Salary Grade Movement record with the updated salary grade and today's date, which keeps the old record untouched for history."
            }
          ]
        },
        {
          id: "position-movement",
          title: "Employee Position Movement",
          route: "/{employee_id}",
          permission: "VIEW EMPLOYEE WEEKLY SCHEDULES",
          screenshot: "employee-position-movement.png",
          description:
            "Tracks an employee's position/title history — promotions, transfers, or reassignments — each with an effective date range. The List of Records table shows Position, Effective Date, End Date (“Present” while open), and Actions.",
          steps: [
            "From List of Employees, open the ⋮ menu on an employee and choose Position Movement.",
            "Click “+ Add Position Movement”.",
            "Choose the new Position and set the Effective Date (and End Date once superseded), then click Save.",
            "To correct the current record, click its pencil icon and click Update.",
            "To remove the latest record, click its trash icon and confirm."
          ],
          tips: [
            "The employee's first position record was created automatically when they were registered, effective on their Date Employed.",
            "Saving, editing or deleting a movement also updates the position shown for that employee in List of Employees.",
            "A new movement cannot be added while the current record has no End Date, and date ranges may not overlap.",
            "Only the current record can be edited, and only the latest record can be deleted (never the first one)."
          ],
          faq: [
            {
              q: "What kind of changes does this page track?",
              a: "An employee's position/title history — promotions, transfers, or reassignments — each with an effective date range."
            },
            {
              q: "How do I correct an existing position movement entry?",
              a: "Use the pencil icon on the current record. Older records are locked, and only the latest record (not the first) can be deleted."
            },
            {
              q: "Why does it say I must set an end date first?",
              a: "An employee can have only one open (no End Date) position record. End the current one, then add the new position."
            }
          ]
        },
        {
          id: "employment-status-movement",
          title: "Employment Status Movement",
          route: "/employment-status-movement/{employee_id}",
          permission: "UPDATE EMPLOYMENT STATUS",
          screenshot: "employment-status-movement.png",
          description:
            "Tracks the dated history of an employee's employment status changes — e.g. moving from Probationary to Permanent — independent from editing the raw field on the employee record.",
          steps: [
            "From List of Employees, open the ⋮ menu on an employee and choose Employment Status Movement.",
            "Click “+ Add Employment Status Movement” to log a new status change with its effective date.",
            "Select the Employment Status and set the Effective Date (and End Date if known), then click Save.",
            "To correct the current record, click its pencil icon and click Update.",
            "To remove the latest record, click its trash icon and confirm with Delete."
          ],
          tips: [
            "The employee's first status record was created automatically when they were registered, effective on their Date Employed.",
            "Saving, editing or deleting a movement also updates the Employment Status shown for that employee in List of Employees.",
            "That status decides whether the employee counts as a “Current Employee” — this affects the Dashboard counts and whether they appear in List of Weekly Schedules and Bulk Scheduling.",
            "A new movement cannot be added while the current record has no End Date, and date ranges may not overlap. Only the current record can be edited and only the latest (not the first) can be deleted."
          ],
          faq: [
            {
              q: "How is this different from editing Employment Status on the Edit Employee page?",
              a: "The Edit Employee form has no Employment Status field. This page keeps the dated history of every status change (e.g. Probationary → Permanent)."
            },
            {
              q: "Can I remove an incorrect status movement entry?",
              a: "Only the latest record can be deleted (never the first one), and only the current record can be edited — use the row's trash or pencil icon."
            },
            {
              q: "What happens to an employee whose status becomes one that is not a Current Employee status (e.g. Resigned)?",
              a: "They drop out of the Active Roster Count on the Dashboard, and out of List of Weekly Schedules and Bulk Scheduling."
            }
          ]
        },
        {
          id: "biometric-ids-list",
          title: "Biometric IDs",
          route: "/biometric-ids",
          permission: "VIEW BIOMETRIC IDS",
          screenshot: "biometric-ids-list.png",
          modalScreenshot: "biometric-ids-list-add-modal.png",
          modalLabel: "Add Biometric ID dialog",
          description:
            "One row per employee — including those who don't have a biometric ID yet — showing each employee's own assigned branch, whether they have a biometric ID on file, and the branch/biometric-ID pairs they've been given. Reached from the Employees sidebar submenu.",
          features: [
            "Filters — Branch (the employee's own assigned branch), Employee, and Status (All / With Biometric ID / Without Biometric ID).",
            "Table columns — Employee No., Employee, Assigned Branch (the employee's own home branch), Status badge (“With Biometric ID” / “Without Biometric ID Yet”), Branch - Biometric ID (every branch/ID pair the employee has, each with its own edit/delete icons), and Actions.",
            "An employee can be given more than one biometric ID, but only one per branch — attempting a second ID for a branch they're already assigned on is rejected.",
            "Per-row + button — opens the Add Biometric ID dialog with that employee already selected.",
            "Add Biometric ID button (top right) — opens the same dialog with the Employee field left blank."
          ],
          steps: [
            "Go to Employees → Biometric IDs in the sidebar.",
            "Optionally filter by Branch, Employee, or Status to narrow the list.",
            "Click “+ Add Biometric ID” (top right), or the small + icon on an employee's row, to open the Add dialog.",
            "Select the Employee (if not already chosen) — their Assigned Branch appears automatically, read-only, for reference.",
            "Select the Branch to register the ID under (this can be different from the employee's assigned branch) and type the Biometric ID, then click Save.",
            "To change a branch/ID pair, click its pencil icon; to remove it, click its trash icon and confirm."
          ],
          tips: [
            "The Assigned Branch field in the Add dialog is read-only — it's just a reference showing the employee's own home branch. The Branch field below it is what actually gets saved with the biometric ID, and it's editable.",
            "One employee can have several biometric IDs (one per branch), and one branch cannot have two employees sharing the same biometric ID."
          ],
          faq: [
            {
              q: "Why does an employee have more than one biometric ID?",
              a: "An employee can be assigned a biometric ID per branch they need to log in at. The Branch - Biometric ID column lists every pair they have."
            },
            {
              q: "I can't add a second biometric ID for the same branch — why?",
              a: "An employee can only have one biometric ID per branch. Edit the existing one for that branch instead."
            },
            {
              q: "What's the difference between “Assigned Branch” and the “Branch” field in the Add dialog?",
              a: "Assigned Branch is read-only — it just shows the employee's own home branch for reference. Branch is the one that actually gets saved with the biometric ID, and you can pick any branch there, not only their assigned one."
            },
            {
              q: "How do I quickly add a biometric ID for one specific employee?",
              a: "Click the small + icon on that employee's row — it opens the Add dialog with the Employee field already filled in."
            }
          ]
        },
        {
          id: "biometric-ids",
          title: "Biometric IDs (per employee)",
          route: "/biometric-ids/{employee_id}",
          permission: "VIEW BIOMETRIC IDS",
          screenshot: "biometric-ids.png",
          description:
            "A single employee's biometric ID assignments — reached from that employee's ⋮ actions menu in List of Employees, or from the main Biometric IDs list. Lets you add, edit, or delete their branch/biometric-ID pairs without leaving their record.",
          steps: [
            "From List of Employees, open the ⋮ menu on an employee and choose Biometric IDs.",
            "Click “+ Add Biometric ID” — the Branch defaults to the employee's own assigned branch, but any branch can be chosen.",
            "Type the Biometric ID and click Save.",
            "To correct a row, click its pencil icon; to remove it, click its trash icon and confirm."
          ],
          tips: [
            "One employee can have only one biometric ID per branch — add further rows for other branches as needed.",
            "Needs the “Delete Biometric IDs” privilege to remove a row; everyone with “View Biometric IDs” can see the list."
          ],
          faq: [
            {
              q: "Can this employee have biometric IDs for more than one branch?",
              a: "Yes — add a separate row for each branch; only one ID per branch per employee is allowed."
            },
            {
              q: "Where else can I manage this employee's biometric IDs?",
              a: "From the main Biometric IDs list (Employees → Biometric IDs), using the row actions or the + button on their row."
            }
          ]
        }
      ]
    },

    // ==================================================EMPLOYEE MEMOS
    {
      id: "memos",
      title: "Employee Memos",
      icon: "doc",
      intro: "A register of memos issued to employees. Each record keeps the subject, the date issued, the employee(s) it was issued to, and the Google Drive link(s) of the memo file.",
      faq: [
        {
          q: "Where is the Employee Memos link in the sidebar?",
          a: "It sits as its own menu item directly below Employees. It only appears for accounts that have the “View Employee Memos” privilege."
        },
        {
          q: "Does the system store the memo file itself?",
          a: "No — the file stays in Google Drive. The system only records the shareable link(s) so the memo can be opened from the list."
        }
      ],
      functions: [
        {
          id: "employee-memos",
          title: "Employee Memos",
          route: "/employee-memos",
          permission: "VIEW EMPLOYEE MEMOS · ADD EMPLOYEE MEMOS · UPDATE EMPLOYEE MEMOS · DELETE EMPLOYEE MEMOS",
          screenshot: "employee-memos.png",
          modalScreenshot: "employee-memos-add-modal.png",
          modalLabel: "Add Memo dialog",
          description:
            "Lists every memo issued to employees in a table you can filter by date issued, employee, and subject. Users with the right privileges can add, edit, and delete memo records; each memo can be addressed to one or more employees and can carry one or more Google Drive file links.",
          features: [
            "Date Issued filter — a From/To range that defaults to the last 2 weeks (14 days ago up to today).",
            "All dates checkbox — tick it to ignore the date range and list every memo; the From/To fields are disabled while it is ticked.",
            "Employee filter — show only the memos issued to one employee (default: All Employees).",
            "Subject filter — show only memos with a chosen subject (default: All Subjects).",
            "Table columns — Date Issued, Subject, Employee(s), File(s) and Action. Each File badge opens that Google Drive link in a new tab.",
            "Add / Edit / Delete actions, each recorded in System Logs."
          ],
          fields: [
            { name: "Subject", description: "The subject line of the memo (up to 255 characters). Required." },
            { name: "Date Issued", description: "The date the memo was issued. Required." },
            { name: "Employee(s)", description: "One or more employees the memo was issued to. Required. You only see employees from the branches assigned to your user type." },
            { name: "Google Drive File Link(s)", description: "One or more shareable links to the memo file. Each must be a drive.google.com or docs.google.com URL; use “+ Add another link” for more than one. At least one is required." }
          ],
          steps: [
            "Click Employee Memos in the sidebar (just below Employees).",
            "The list opens on the last 2 weeks. Change the Date Issued range, or tick All dates, then optionally pick an Employee and/or a Subject to narrow the list.",
            "To record a memo, click “Add Memo”.",
            "Enter the Subject, choose the Date Issued, and select one or more Employee(s).",
            "Paste the Google Drive link of the memo file. Click “+ Add another link” if there is more than one file.",
            "Click Save. The memo appears in the table and the action is written to System Logs as “Add Employee Memo”.",
            "To change a memo, click the pencil icon on its row, update the fields, and Save (logged as “Update Employee Memo”).",
            "To remove a memo, click the trash icon and confirm (logged as “Delete Employee Memo”). The file in Google Drive is not touched."
          ],
          tips: [
            "Make sure the Google Drive file is shared with the people who need to open it — the system only stores the link, not the permissions.",
            "If a memo you just added is not visible, check the Date Issued range (or tick All dates) — memos outside the selected range are hidden.",
            "Open System Logs and use the View (eye) icon on an “Add / Update / Delete Employee Memo” entry to see exactly which subject, employees and links were recorded."
          ],
          faq: [
            {
              q: "Can one memo be issued to several employees?",
              a: "Yes — select as many employees as needed in the Employee(s) field; the memo is listed once with all of them shown."
            },
            {
              q: "Can I attach more than one file to a memo?",
              a: "Yes — use “+ Add another link” to add more Google Drive links. Each one shows as a File badge in the table."
            },
            {
              q: "Why does the system reject my link?",
              a: "Only Google Drive / Google Docs links (drive.google.com or docs.google.com) are accepted, the same link cannot be entered twice, and at least one link is required."
            },
            {
              q: "Why do I only see some employees in the list?",
              a: "The Employee dropdown and the memo list are limited to the branches assigned to your user type under System Settings → User Type & Permissions."
            },
            {
              q: "What does the default date range show?",
              a: "Memos issued in the last 2 weeks. Change the From/To dates, or tick All dates to see every memo."
            },
            {
              q: "I can't see the Employee Memos menu or the Add / Edit / Delete buttons — why?",
              a: "Each action needs its own privilege (View, Add, Update, Delete Employee Memos). An administrator can grant them under System Settings → User Type & Permissions."
            }
          ]
        }
      ]
    },

    // ==================================================PERFORMANCE EVALUATION
    {
      id: "evaluations",
      title: "Performance Evaluation",
      icon: "award",
      intro: "A register of employee performance evaluation results. Each record keeps the evaluation period, type, date, overall score and rating, remarks, and the Google Drive link(s) of the evaluation file.",
      faq: [
        {
          q: "Where is the Performance Evaluation link in the sidebar?",
          a: "It sits as its own menu item directly below Employee Memos. It only appears for accounts that have the “View Performance Evaluations” privilege."
        },
        {
          q: "Does the system store the evaluation file itself?",
          a: "No — the file stays in Google Drive. The system only records the shareable link(s) so the evaluation can be opened from the list."
        }
      ],
      functions: [
        {
          id: "performance-evaluations",
          title: "Performance Evaluation",
          route: "/performance-evaluations",
          permission: "VIEW PERFORMANCE EVALUATIONS · ADD PERFORMANCE EVALUATIONS · UPDATE PERFORMANCE EVALUATIONS · DELETE PERFORMANCE EVALUATIONS",
          screenshot: "performance-evaluations.png",
          modalScreenshot: "performance-evaluations-add-modal.png",
          modalLabel: "Add Evaluation dialog",
          description:
            "Lists every performance evaluation result in a table you can filter by year, employee, type, and rating. Users with the right privileges can add, edit, and delete evaluation records; each evaluation is for one employee and can carry one or more Google Drive file links.",
          features: [
            "Year filter — show only evaluations from a chosen year (defaults to the current year; default: All Years lists every year).",
            "Employee filter — show only the evaluations of one employee (default: All Employees).",
            "Evaluation Type filter — show only evaluations of a chosen type (default: All Types).",
            "Rating filter — show only evaluations with a chosen overall rating (default: All Ratings).",
            "Table columns — Evaluation Date, Employee, Period/Type, Score/Rating, File(s) and Action. Each File badge opens that Google Drive link in a new tab.",
            "Add / Edit / Delete actions, each recorded in System Logs."
          ],
          fields: [
            { name: "Employee", description: "The employee being evaluated. Required. You only see employees from the branches assigned to your user type." },
            { name: "Evaluation Period", description: "The period the evaluation covers, e.g. “Jan – Jun 2026” (up to 100 characters). Required." },
            { name: "Evaluation Type", description: "e.g. Probationary, Quarterly, Semi-Annual, Annual, Regularization, Special / Ad-Hoc. Pick one from the list or type your own. Required." },
            { name: "Evaluation Date", description: "The date the evaluation was conducted or finalized. Required." },
            { name: "Overall Score", description: "A numeric score from 0 to 100. Required." },
            { name: "Overall Rating", description: "e.g. Outstanding, Very Satisfactory, Satisfactory, Fair, Poor. Pick one from the list or type your own. Required." },
            { name: "Remarks", description: "Optional notes about the evaluation." },
            { name: "Google Drive File Link(s)", description: "One or more shareable links to the evaluation file. Each must be a drive.google.com or docs.google.com URL; use “+ Add another link” for more than one. At least one is required." }
          ],
          steps: [
            "Click Performance Evaluation in the sidebar (just below Employee Memos).",
            "The list opens on the current year. Change the Year filter (or choose All Years), then optionally pick an Employee, Evaluation Type, and/or Rating to narrow the list.",
            "To record an evaluation, click “Add Evaluation”.",
            "Select the Employee, enter the Evaluation Period, choose the Evaluation Type, and set the Evaluation Date.",
            "Enter the Overall Score and choose the Overall Rating, and add Remarks if needed.",
            "Paste the Google Drive link of the evaluation file. Click “+ Add another link” if there is more than one file.",
            "Click Save. The evaluation appears in the table and the action is written to System Logs as “Add Performance Evaluation”.",
            "To change an evaluation, click the pencil icon on its row, update the fields, and Save (logged as “Update Performance Evaluation”).",
            "To remove an evaluation, click the trash icon and confirm (logged as “Delete Performance Evaluation”). The file in Google Drive is not touched."
          ],
          tips: [
            "Make sure the Google Drive file is shared with the people who need to open it — the system only stores the link, not the permissions.",
            "If an evaluation you just added is not visible, check the Year filter (or switch it to All Years) — evaluations from other years are hidden.",
            "The Evaluation Type and Overall Rating fields offer a preset list but also accept a typed-in value, so you are not limited to the defaults.",
            "Open System Logs and use the View (eye) icon on an “Add / Update / Delete Performance Evaluation” entry to see exactly which employee, period, score, rating and links were recorded."
          ],
          faq: [
            {
              q: "Can one evaluation cover more than one employee?",
              a: "No — each evaluation record is for a single Employee. Create a separate record for each employee being evaluated."
            },
            {
              q: "Can I attach more than one file to an evaluation?",
              a: "Yes — use “+ Add another link” to add more Google Drive links. Each one shows as a File badge in the table."
            },
            {
              q: "Why does the system reject my file link?",
              a: "Only Google Drive / Google Docs links (drive.google.com or docs.google.com) are accepted, the same link cannot be entered twice, and at least one link is required."
            },
            {
              q: "Do the Evaluation Type and Overall Rating have to match the preset list?",
              a: "No — both fields let you pick a preset value or type your own, so you can record a type or rating scale that isn't in the default list."
            },
            {
              q: "What range must the Overall Score fall in?",
              a: "0 to 100. The field is required on every evaluation."
            },
            {
              q: "How does the Year filter decide which years are listed?",
              a: "It lists every year that has at least one evaluation on record, plus the current year even if it has none yet, newest first."
            },
            {
              q: "Why do I only see some employees in the list?",
              a: "The Employee dropdown and the evaluation list are limited to the branches assigned to your user type under System Settings → User Type & Permissions."
            },
            {
              q: "I can't see the Performance Evaluation menu or the Add / Edit / Delete buttons — why?",
              a: "Each action needs its own privilege (View, Add, Update, Delete Performance Evaluations). An administrator can grant them under System Settings → User Type & Permissions."
            }
          ]
        }
      ]
    },

    // ==================================================ATTENDANCE MANAGEMENT
    {
      id: "attendance",
      title: "Attendance Management",
      icon: "clock",
      intro: "Timekeeping-related records: company-wide holidays, travel/seminar requests, branch visits, and employee suspensions.",
      faq: [
        {
          q: "What kinds of records live in this module?",
          a: "Company-wide holidays and non-working days, on-travel/seminar/training requests, branch visits, and employee suspensions."
        },
        {
          q: "Can these records be scoped to a specific branch?",
          a: "Holidays can be applied to all branches or specific ones, and Travel Requests / Branch Visits / Suspensions can all be filtered by branch on their list pages."
        }
      ],
      functions: [
        {
          id: "holidays",
          title: "Holidays & Non-Working Days",
          route: "/holidays",
          permission: "VIEW HOLIDAYS & NON-WORKING DAYS · ADD HOLIDAYS & NON-WORKING DAYS · UPDATE HOLIDAYS & NON-WORKING DAYS · DELETE HOLIDAYS & NON-WORKING DAYS",
          screenshot: "holidays.png",
          modalScreenshot: "holidays-add-modal.png",
          modalLabel: "New Holiday dialog",
          description:
            "The company-wide calendar of regular holidays, special non-working days, and other declared off-days, optionally scoped to specific branches.",
          features: [
            "Filters — Type (All Types, Regular Holiday, Special Non-Working, Company, Government), Year (defaults to the current year; All Years lists every year), and a Search box for the holiday name.",
            "Table columns — Holiday / Day, Date, Type, Duration, Recurring, Applied Branches, Description, and Action.",
            "Add / edit / delete actions, gated by the Add, Update and Delete privileges for this page.",
            "Branch scoping — if your user type is limited to certain branches, you only see holidays that apply to all branches or to your branches, and only your branches are offered in the dialog."
          ],
          fields: [
            { name: "Holiday / Day Name", description: "e.g. “Christmas Day”, “Typhoon Suspension”. Required." },
            { name: "Date", description: "The calendar date of the holiday. Required." },
            { name: "Type", description: "Regular Holiday, Special Non-Working, Company, or Government. Required." },
            { name: "Coverage", description: "Whole Day, or Specific Time Range for partial-day suspensions — when chosen, a Time Start and Time End (both required) appear." },
            { name: "Description", description: "Optional notes explaining the holiday." },
            { name: "Recurring annually", description: "Tick to repeat the holiday on the same date every year." },
            { name: "Applied to Branches", description: "Leave every branch unticked to apply the holiday to all branches, tick specific branches to scope it, or use Select All." }
          ],
          steps: [
            "Go to Attendance Management → Holidays & Non-Working Days.",
            "Use the Type, Year, or Search filters to find existing entries.",
            "Click “Add Holiday”.",
            "Enter the Holiday/Day Name, Date, and Type.",
            "Choose Whole Day or Specific Time Range under Coverage (and the Time Start / Time End for a time range).",
            "Optionally add a Description, tick Recurring annually, and select which branches it applies to.",
            "Click Save.",
            "Use the row action icons to edit or delete an existing holiday entry."
          ],
          faq: [
            {
              q: "Can a holiday apply to only some branches?",
              a: "Yes — leave Applied to Branches empty to apply it to all branches, or select specific ones to scope it."
            },
            {
              q: "What's the difference between Whole Day and Specific Time Range coverage?",
              a: "Whole Day covers the entire date, while Specific Time Range is for partial-day suspensions with defined start/end times."
            },
            {
              q: "Can I make a holiday repeat automatically every year?",
              a: "Yes — check “Recurring annually” when creating or editing it."
            }
          ]
        },
        {
          id: "travel-requests",
          title: "On-Travel / Seminar / Training",
          route: "/travel-requests",
          permission: "VIEW ON-TRAVEL & SEMINAR REQUESTS · ADD ON-TRAVEL & SEMINAR REQUESTS · UPDATE ON-TRAVEL & SEMINAR REQUESTS · DELETE ON-TRAVEL & SEMINAR REQUESTS",
          screenshot: "travel-requests.png",
          modalScreenshot: "travel-requests-add-modal.png",
          modalLabel: "New Request dialog",
          description:
            "Logs employees who are away from their regular post for an official on-travel assignment, seminar, or training, over a specific date range, with the daily time schedule for each day away.",
          features: [
            "Filters — Branch, Type (On-Travel, Seminar, Training), Purpose, and a Date From / Date To range.",
            "Table columns — Employee, Type, Start Date, End Date, Days, Purpose, and Action.",
            "Daily Schedule — once the Start and End Date are chosen, the dialog lists every date in the range with its own Time Start and Time End.",
            "Add Record button (top right) plus edit and delete row actions, gated by the Add, Update and Delete privileges."
          ],
          fields: [
            { name: "Employee(s)", description: "The employee(s) covered by this request. Required." },
            { name: "Type", description: "On-Travel, Seminar, or Training. Required." },
            { name: "Purpose / Title", description: "A short description of the reason for travel. Required (up to 255 characters)." },
            { name: "Start Date / End Date", description: "The date range the employee is away. Required." },
            { name: "Daily Schedule", description: "A Date / Day / Time Start / Time End row for each day in the range, so partial days can be recorded accurately." },
            { name: "Notes", description: "Optional additional remarks." }
          ],
          steps: [
            "Go to Attendance Management → On-Travel / Seminar / Training.",
            "Use the Branch, Type, Purpose, or Date filters to narrow existing records.",
            "Click “Add Record” to log a new request.",
            "Select the employee(s), Type, Purpose, and the Start/End Date range.",
            "Review the Daily Schedule that appears for the chosen dates and adjust each day's Time Start / Time End if needed, then click Save.",
            "Use the row action icons to edit or delete a request."
          ],
          faq: [
            {
              q: "What types of requests can I log here?",
              a: "On-Travel, Seminar, or Training — chosen from the Type field when creating a request."
            },
            {
              q: "Can one request cover more than one employee?",
              a: "Yes — the Employee(s) field accepts more than one person for the same travel/seminar/training request."
            },
            {
              q: "Are Notes required when logging a request?",
              a: "No — Notes are optional; Employee(s), Type, Purpose, and the date range are the required fields."
            }
          ]
        },
        {
          id: "branch-visits",
          title: "Branch Visits",
          route: "/branch-visits",
          permission: "VIEW BRANCH VISITS · ADD BRANCH VISITS · UPDATE BRANCH VISITS · DELETE BRANCH VISITS",
          screenshot: "branch-visits.png",
          modalScreenshot: "branch-visits-add-modal.png",
          modalLabel: "New Branch Visit dialog",
          description:
            "Records employees who are temporarily working out of a branch other than their home branch — useful for auditing attendance across locations — with the daily time schedule for each day of the visit.",
          features: [
            "Filters — Employee Branch, Visited Branch, and a Date From / Date To range.",
            "Table columns — Employee, Branches Visited, Start Date, End Date, Days, Purpose, and Action.",
            "Daily Schedule — once the Start and End Date are chosen, the dialog lists every date in the range with its own Time Start and Time End.",
            "Add Record button (top right) plus edit and delete row actions, gated by the Add, Update and Delete privileges."
          ],
          fields: [
            { name: "Employee(s)", description: "The employee(s) making the visit. Required." },
            { name: "Branch(es) Visited", description: "One or more destination branches. Required." },
            { name: "Purpose / Title", description: "Reason for the visit. Required." },
            { name: "Start Date / End Date", description: "The date range covering the visit. Required." },
            { name: "Daily Schedule", description: "A Date / Day / Time Start / Time End row for each day in the range." },
            { name: "Notes", description: "Optional additional remarks." }
          ],
          steps: [
            "Go to Attendance Management → Branch Visits.",
            "Use the Employee Branch, Visited Branch, or Date filters to narrow existing records.",
            "Click “Add Record” to log a new visit.",
            "Select the employee(s), the branch(es) being visited, Purpose, and the Start/End Date range.",
            "Review the Daily Schedule for the chosen dates, adjust the times if needed, then click Save.",
            "Use the row action icons to edit or delete a visit record."
          ],
          faq: [
            {
              q: "Can one visit record cover more than one destination branch?",
              a: "Yes — Branch(es) Visited accepts one or more destination branches in the same record."
            },
            {
              q: "How do I filter existing visit records?",
              a: "Use the Employee Branch, Visited Branch, or Date filters above the list."
            }
          ]
        },
        {
          id: "suspensions",
          title: "Suspended Employees",
          route: "/suspensions",
          permission: "VIEW SUSPENDED EMPLOYEES · ADD SUSPENDED EMPLOYEES · UPDATE SUSPENDED EMPLOYEES · DELETE SUSPENDED EMPLOYEES",
          screenshot: "suspensions.png",
          modalScreenshot: "suspensions-add-modal.png",
          modalLabel: "New Suspension dialog",
          description:
            "Tracks employees who are under a work suspension, including the date range, total number of days, and the reason.",
          features: [
            "Branch filter — show only the suspensions of employees in one branch (default: All Branches).",
            "Table columns — Employee, Branch, Start Date, End Date, Total Days, Reason, and Action.",
            "Add Record button (top right) plus edit and delete row actions, gated by the Add, Update and Delete privileges."
          ],
          fields: [
            { name: "Employee", description: "The employee being suspended. Required." },
            { name: "Start Date / End Date", description: "The suspension period. Required." },
            { name: "Total Days", description: "Computed automatically from the Start and End Date and shown as a number of calendar days — you do not type it." },
            { name: "Reason / Remarks", description: "Optional explanation for the suspension." }
          ],
          steps: [
            "Go to Attendance Management → Suspended Employees.",
            "Optionally filter existing records by Branch.",
            "Click “Add Record” to log a new suspension.",
            "Select the Employee, set the Start/End Date (Total Days fills in by itself), and optionally add a Reason, then Save.",
            "Use the row action icons to edit or delete a suspension record."
          ],
          faq: [
            {
              q: "Do I need to calculate Total Days myself?",
              a: "No — Total Days is computed automatically from the Start and End Date, counted in calendar days."
            },
            {
              q: "Is a Reason required when logging a suspension?",
              a: "No — Reason / Remarks is optional; Employee and the Start/End Date are required."
            }
          ]
        }
      ]
    },

    // ============================================================ COMPANY SETUP
    {
      id: "company-setup",
      title: "Company Setup",
      icon: "briefcase",
      intro: "The master data that the rest of the system draws from — branches / office locations, the job-position catalog, employment-status labels, and the salary-grade structure.",
      faq: [
        {
          q: "Where do I find Branches, Company Positions, Employment Status and Salary Grade now?",
          a: "They all live under the Company Setup menu in the sidebar, just above System Settings."
        },
        {
          q: "Why are these grouped together?",
          a: "Each one is a reference list that other modules reuse — employees, holidays, movements and filters all pick from the values defined here."
        }
      ],
      functions: [
        {
          id: "manage-branches",
          title: "Manage Branches",
          route: "/branches",
          permission: "VIEW BRANCHES · CREATE NEW BRANCH · UPDATE BRANCH · DELETE BRANCH",
          screenshot: "branches.png",
          modalScreenshot: "branches-add-modal.png",
          modalLabel: "Add Branch dialog",
          description:
            "Branches represent the physical offices or outlets your organization operates. Every branch created here becomes selectable elsewhere in the system — assigning employees, filtering the employee list, restricting holidays to specific branches, and logging branch visits.",
          features: [
            "Area filter (top of the page) — narrows the list to one Area number, or All Areas.",
            "Table columns — Branch Code, Branch Name, Area, Address, Visible?, and Actions.",
            "Create New Branch button (top right) — shown with the “Create New Branch” privilege.",
            "Edit (pencil) and Delete (trash) row actions — shown with the “Update Branch” and “Delete Branch” privileges."
          ],
          fields: [
            { name: "Branch Code", description: "A short unique identifier for the branch (e.g. “Branch 02”), up to 20 characters. Required, must not already exist, and cannot be changed after the branch is created." },
            { name: "Branch Name", description: "The full, human-readable name of the branch/office. Required." },
            { name: "Area", description: "An optional whole number used to group branches (e.g. by region or cluster). Leave blank if you don't need grouping." },
            { name: "Address", description: "Street or area address of the branch. Optional." },
            { name: "Visible", description: "Tick to make the branch appear in dropdowns and filters system-wide (ticked by default)." }
          ],
          steps: [
            "Open Company Setup → Branches from the sidebar.",
            "Click “Create New Branch” in the upper-right corner of the page.",
            "Fill in the Branch Code, Branch Name, optional Area, and optional Address.",
            "Leave “Visible” ticked so the branch is selectable elsewhere in the system, then click Create.",
            "To edit a branch, click the pencil icon on its row, update the fields in the dialog, and click Update.",
            "To remove a branch, click the trash icon on its row and confirm.",
            "Use the Area filter at the top of the page to see only the branches in a particular group."
          ],
          tips: [
            "Deleting a branch does not automatically reassign employees already tied to it — reassign employees first if needed.",
            "Untick “Visible” instead of deleting when you want to retire a branch but keep its historical records intact.",
            "A new branch only shows up in an existing user type's employee lists once it is ticked under that user type's Assigned Branches (System Settings → User Type & Permissions).",
            "Area is just a grouping number you define yourself — use whatever numbering makes sense for your organization (e.g. by region)."
          ],
          faq: [
            {
              q: "Should I delete a branch or just hide it?",
              a: "Untick “Visible” instead of deleting when you want to retire a branch but keep its historical records intact — deleting removes it from the list."
            },
            {
              q: "Why can't I see my new branch's employees?",
              a: "Users only see branches assigned to their user type. Tick the new branch under Assigned Branches for that user type in System Settings → User Type & Permissions."
            },
            {
              q: "Can I change a branch's code later?",
              a: "No — the Branch Code is locked once the branch exists. You can edit the name, address and visibility."
            },
            {
              q: "What happens to employees assigned to a branch I delete?",
              a: "They are not automatically reassigned — reassign those employees to another branch first if needed."
            },
            {
              q: "What is the Area column/filter for?",
              a: "It's an optional grouping number for branches — use it however suits your organization (e.g. by region or cluster), then use the Area filter at the top of the page to view just one group at a time."
            },
            {
              q: "Which fields are required when adding a branch?",
              a: "Branch Code and Branch Name are required; Address is optional."
            }
          ]
        },
        {
          id: "company-positions",
          title: "Company Positions",
          route: "/positions/company_positions",
          permission: "VIEW COMPANY POSITIONS · EDIT COMPANY POSITIONS · RESTORE AND ARCHIVE COMPANY POSITION",
          screenshot: "company-positions.png",
          modalScreenshot: "company-positions-add-modal.png",
          modalLabel: "Add Position dialog",
          description:
            "The catalog of job titles/positions available in the organization (e.g. HR Manager, HR Assistant Manager). Each position also shows how many employees currently hold it.",
          fields: [
            { name: "Position Name", description: "The job title, e.g. “HR Manager”, up to 150 characters. Required, and must not already exist." }
          ],
          features: [
            "Status filter — Active (default) or Archived.",
            "Table columns — Position, No. of Employees, and Actions (edit, and archive or restore).",
            "Create New Position button in the top-right corner."
          ],
          steps: [
            "Go to Company Setup → Company Positions.",
            "Optionally set the Status filter to Active or Archived.",
            "Click “Create New Position”, enter the Position Name, and click Create.",
            "Use the row's edit (pencil) action to rename a position, then click Update.",
            "Use the archive action to retire a position without deleting its historical usage, or switch the filter to Archived and restore a previously archived one."
          ],
          tips: [
            "Archiving a position hides it from new-assignment dropdowns but keeps it visible for employees already holding it."
          ],
          faq: [
            {
              q: "What's the difference between archiving and deleting a position?",
              a: "Archiving hides the position from new-assignment dropdowns but keeps it visible for employees already holding it — there's no separate delete action, just archive and restore."
            },
            {
              q: "Can I tell how many employees hold a given position?",
              a: "Yes — the list on this page shows how many employees currently hold each position."
            }
          ]
        },
        {
          id: "employment-status",
          title: "Employment Status",
          route: "/employment_status/load",
          permission: "VIEW EMPLOYMENT STATUS · ADD NEW EMPLOYMENT STATUS · UPDATE EMPLOYMENT STATUS",
          screenshot: "employment-status.png",
          modalScreenshot: "employment-status-add-modal.png",
          modalLabel: "Add Employment Status dialog",
          description:
            "Maintains the set of employment status labels used across the system (e.g. Permanent, Probationary, Resigned) and shows how many employees currently carry each status.",
          features: [
            "Table columns — Employment Status, No. of Employees, Current Employee, Visible?, and Actions (edit).",
            "Create Employment Status button in the top-right corner."
          ],
          fields: [
            { name: "Employment Status", description: "The label text, e.g. “Probationary”. Required, and must not already exist." },
            { name: "Is Current Employee?", description: "Yes or No. Marks whether this status still counts the employee as an active member of the roster." },
            { name: "Is Visible?", description: "Yes or No. Controls whether the status appears in selection dropdowns." }
          ],
          steps: [
            "Go to Company Setup → Employment Status.",
            "Click “Create Employment Status” to define a new status label.",
            "Set Is Current Employee and Is Visible according to how this status should behave, then click Create.",
            "Use the row's edit (pencil) action to rename or adjust an existing status, then click Update."
          ],
          tips: [
            "To change one employee's status with a dated, auditable record, use Employment Status Movement from the employee's row action menu — this page only manages the master list of status labels.",
            "“Is Current Employee?” has real consequences: employees whose status is set to No drop out of the Dashboard's Active Roster Count (they count as Inactive/Archived) and disappear from List of Weekly Schedules and Bulk Scheduling."
          ],
          faq: [
            {
              q: "What does “Is Current Employee?” control?",
              a: "Whether this status still counts the employee as an active member of the roster (e.g. Resigned would typically be set to No). Employees on a No status are counted as Inactive/Archived on the Dashboard and are left out of List of Weekly Schedules and Bulk Scheduling."
            },
            {
              q: "How do I change one specific employee's status with an effective date?",
              a: "Use Employment Status Movement from that employee's row action menu — this page only manages the master list of status labels, not individual assignments."
            }
          ]
        },
        {
          id: "salary-grade",
          title: "Salary Grade",
          route: "/salary-grades",
          permission: "VIEW SALARY GRADE · ADD NEW SALARY GRADE · UPDATE SALARY GRADE",
          screenshot: "salary-grades.png",
          modalScreenshot: "salary-grades-add-modal.png",
          modalLabel: "Add Salary Grade dialog",
          description:
            "Defines the organization's salary structure — each grade level and step combination carries a Basic Salary, Daily Rate, and Hourly Rate that can later be assigned to employees through Salary Grade Movement.",
          features: [
            "Table columns — Grade Level, Step, Basic Salary, Daily Rate, Hourly Rate, Status (Active / Inactive), and Actions (edit and delete).",
            "Create Salary Grade button in the top-right corner.",
            "Daily and Hourly Rate are calculated for you and shown read-only in the dialog."
          ],
          fields: [
            { name: "Grade Level", description: "The numeric salary grade, from 1 to 100. Required." },
            { name: "Step", description: "The increment step within that grade level, from 1 to 100. Required. A Grade Level + Step combination can only exist once." },
            { name: "Basic Salary", description: "The monthly basic salary for this grade/step, in pesos (₱). Required." },
            { name: "Daily Rate", description: "Read-only — calculated automatically as Basic Salary ÷ 26." },
            { name: "Hourly Rate", description: "Read-only — calculated automatically as Daily Rate ÷ 8." },
            { name: "Active", description: "Whether this grade/step is currently usable (ticked by default)." }
          ],
          steps: [
            "Go to Company Setup → Salary Grade.",
            "Click “Create Salary Grade” to open the dialog.",
            "Enter the Grade Level, Step and Basic Salary — the Daily Rate and Hourly Rate fill in by themselves.",
            "Leave Active ticked and click Create. If that Grade Level and Step already exist, you are told the salary grade already exists.",
            "To adjust a grade later, click the row's pencil icon, change the values, and click Update — this does not retroactively change movements already recorded for employees.",
            "To remove a grade, click the trash icon on its row and confirm."
          ],
          tips: [
            "The Daily and Hourly Rate are recalculated from the Basic Salary every time you save, so you cannot type them in."
          ],
          faq: [
            {
              q: "How do I actually assign a salary grade to an employee?",
              a: "Grades defined here are later assigned to employees through Salary Grade Movement, reached from that employee's row in List of Employees."
            },
            {
              q: "If I edit a grade's rate later, does it change employees' past records?",
              a: "No — editing a grade/step here does not retroactively change movements already recorded for employees."
            },
            {
              q: "What rates does each grade/step carry, and how are they worked out?",
              a: "A Basic Salary that you enter, plus a Daily Rate (Basic Salary ÷ 26) and an Hourly Rate (Daily Rate ÷ 8) that the system calculates for you."
            },
            {
              q: "Can I delete a salary grade?",
              a: "Yes — use the trash icon on its row and confirm. The deletion is written to System Logs as “Delete Salary Grade”."
            }
          ]
        }
      ]
    },

    // =========================================================SYSTEM SETTINGS
    {
      id: "settings",
      title: "System Settings",
      icon: "settings",
      intro: "Administrative controls: user accounts, role-based permissions, system activity logs, and your own account profile.",
      faq: [
        {
          q: "Where do I control what a role is allowed to see or do?",
          a: "User Type & Permissions — every sidebar item and page action in the system is gated by the checkboxes defined there."
        },
        {
          q: "Where can I check what actions were recently taken in the system?",
          a: "System Logs — a chronological audit trail of logins, logouts, and record changes."
        }
      ],
      functions: [
        {
          id: "user-accounts",
          title: "User Accounts",
          route: "/system/user_accounts",
          permission: "VIEW USER ACCOUNTS",
          screenshot: "user-accounts.png",
          modalScreenshot: "user-accounts-register-modal.png",
          modalLabel: "Register New User dialog",
          description:
            "Manage the login accounts that can access the HRIS — register new accounts, and change an existing account's User Type (role), status, details, or password. Each account's User Type determines its permissions.",
          features: [
            "Filters — User Type and Account Status (All, Active, Inactive).",
            "Table columns — Name, Username, Email, User Type, Status, and Actions (edit).",
            "Register New User button (top right) — creates a brand-new account.",
            "Edit dialog — change name, username, email, User Type, Account Status, and optionally reset the password."
          ],
          fields: [
            { name: "Full Name", description: "The account holder's name. Required." },
            { name: "Username", description: "The sign-in name, up to 20 characters and unique. Required when registering." },
            { name: "Email", description: "Optional, but must be a valid, unique email address when filled in." },
            { name: "User Type", description: "The role assigned to the account, which determines its permissions. Required when registering." },
            { name: "Account Status", description: "Active or Inactive — inactive accounts cannot sign in." },
            { name: "Password / New Password", description: "Required when registering (6 to 72 characters, with a show/hide eye button). When editing, leave “New Password” blank to keep the current password, or fill it in to reset it." }
          ],
          steps: [
            "Go to System Settings → User Accounts.",
            "Use the User Type or Account Status filters to narrow the list, or use Quick Filter to search by name.",
            "To create an account, click Register New User, fill in the Full Name, Username, User Type, Account Status and Password (Email is optional), and click Register.",
            "To change an account, click the edit (pencil) action on its row to open the account dialog.",
            "Change the details, User Type and/or Account Status as needed.",
            "Optionally enter a New Password to reset the account's credentials, then click Update."
          ],
          tips: [
            "Registering an employee under Employees → Employee Registration also creates a login account (username = Employee ID). It stays unusable until you assign it a User Type here.",
            "Registering and updating accounts is recorded in System Logs (“Registered New User”, “Update User Account”); a password reset is noted in the entry without showing the password."
          ],
          faq: [
            {
              q: "How do I create a brand-new login account?",
              a: "Click Register New User at the top right of this page, complete the form, and click Register. Accounts are also created automatically when an employee is registered, but those need a User Type assigned here before they can be used."
            },
            {
              q: "Why does the system reject my new username or email?",
              a: "Usernames must be unique and no longer than 20 characters, and an email address must be valid and not used by another account."
            },
            {
              q: "How do I reset someone's password?",
              a: "Open the edit action on their row and enter a value in the New Password field, then Save."
            },
            {
              q: "What happens if I set an account's status to Inactive?",
              a: "That account can no longer sign in until it's switched back to Active."
            }
          ]
        },
        {
          id: "user-permissions",
          title: "User Type & Permissions",
          route: "/system/user_permissions",
          permission: "VIEW USER TYPE & PERMISSIONS",
          screenshot: "user-permissions.png",
          modalScreenshot: "user-permissions-add-modal.png",
          modalLabel: "Create User Type dialog",
          description:
            "Defines the roles (User Types) available in the system — such as Administrator, HR Admin, and Employee — the exact set of privileges each role is granted, grouped by module, and the branches each role is allowed to see. The page is titled “User Types & Privileges”.",
          features: [
            "Table columns — User Type (shown as a colour badge), User Accounts (how many accounts use the role), and Actions (edit and delete).",
            "Privilege groups — Branches, Employee Management (including Delete Employee), Employee Memos, Performance Evaluation, Weekly Schedules, Salary Grade, Company Positions, Employment Status, System Settings, Holidays & Non-Working Days, Suspended Employees, On-Travel & Seminar Requests, and Branch Visits.",
            "Assigned Branches — a checklist of branches. Users of this role only see employees, schedules, memos, evaluations and other records belonging to the ticked branches.",
            "Delete (trash) action — removes a user type after a confirmation prompt."
          ],
          fields: [
            { name: "User Type Name", description: "The role's display name, e.g. “HR Admin” (up to 50 characters). Required." },
            { name: "Badge Color", description: "A colour used to tag the role — pick a swatch or a custom colour; a live preview shows the badge." },
            { name: "Privileges & Access", description: "A checklist of individual permissions grouped by module — tick every action this role should be allowed to perform. Select All ticks the whole panel." },
            { name: "Assigned Branches", description: "The branches whose records this role may see. A role with no branch ticked sees no branch data." }
          ],
          steps: [
            "Go to System Settings → User Type & Permissions.",
            "Click “Create User Type” to define a new role.",
            "Enter the User Type Name and pick a Badge Color.",
            "Under Privileges & Access, tick every permission this role should have (or use Select All).",
            "Under Assigned Branches, tick the branches this role may work with.",
            "Click Create to save the role.",
            "Use the pencil icon on an existing role to adjust its name, colour, privileges, or branches, then click Update.",
            "Use the trash icon and confirm to delete a role that is no longer needed."
          ],
          tips: [
            "Sidebar menu items and page actions throughout the whole system are shown or hidden based on these exact privilege checkboxes — if a menu item is missing for a user, check their role's privileges here first.",
            "If a user can open a page but sees no employees or records, check the role's Assigned Branches.",
            "New privileges added by an update (for example Delete Employee) are not ticked for existing roles — tick them on each role that should have them."
          ],
          faq: [
            {
              q: "How do I give a role full access to one entire module?",
              a: "Tick every checkbox in that module's group of the Privileges & Access checklist; Select All ticks the whole panel at once."
            },
            {
              q: "What are Assigned Branches for?",
              a: "They limit which branches' employees and records a user of that role can see in lists such as List of Employees, Weekly Schedules, Memos and Evaluations."
            },
            {
              q: "What happens to accounts when I delete a user type?",
              a: "The role is removed from the list and the deletion is written to System Logs as “Delete User Type”. Reassign the affected accounts to another User Type under User Accounts."
            },
            {
              q: "Why is a sidebar item missing for one of my users?",
              a: "Check that user's role here first — sidebar items and page actions system-wide are shown or hidden based on the exact privilege checkboxes set for their User Type."
            },
            {
              q: "What is the Badge Color used for?",
              a: "It visually tags that role throughout the UI, so accounts of that User Type are easy to spot at a glance."
            }
          ]
        },
        {
          id: "system-logs",
          title: "System Logs",
          route: "/system/system_logs",
          permission: "VIEW SYSTEM LOGS",
          screenshot: "system-logs.png",
          modalScreenshot: "system-logs-view-modal.png",
          modalLabel: "Log Details window",
          description:
            "A chronological audit trail of actions performed across the system — logins, logouts, and record changes — each entry showing what happened, who performed it, and when. Every row has a View action that opens the full detail of what was recorded for that entry.",
          features: [
            "Date filter — pick a day to show only the entries logged on that date.",
            "All dates checkbox — tick it to remove the date limit and list activity from every date; the Date field is disabled while it is ticked. Untick it to go back to a single day.",
            "User filter — limit the list to a single account's activity (or All).",
            "Activity filter — limit the list to one kind of action, e.g. “Logged in”, “Updated Employee Information” (or All).",
            "Table columns — Date & Time, User, Activity, and an Actions column.",
            "View button (the eye icon) — opens the Log Details window for that entry.",
            "Log Details window — shows the Date & Time, User, and Activity, plus a Details section. When the entry stored a data snapshot (for example the fields submitted on an employee update) each field is listed as a readable label/value table; entries with no extra data simply say so.",
            "Quick filter / search box and pagination on the table itself for scanning long lists."
          ],
          steps: [
            "Go to System Settings → System Logs.",
            "Set the Date, User, and Activity filters to narrow the list — the table refreshes automatically when you change a filter.",
            "To review activity across every date instead of a single day, tick the All dates checkbox next to the Date field (the Date field greys out while it is ticked); untick it to return to a specific day.",
            "Review each entry's Activity description, the responsible User, and the timestamp.",
            "Click the View (eye) icon on any row to open the Log Details window and see everything that was recorded for that entry.",
            "In the Log Details window, read the field-by-field snapshot in the Details section, then click Close.",
            "Use this page to investigate unexpected changes or confirm that an action (e.g. an employee update) was successfully recorded."
          ],
          tips: [
            "The Details snapshot reflects exactly what was submitted at the time — comparing two consecutive entries for the same record shows precisely which fields changed.",
            "If the View window shows “No additional details were recorded for this entry”, the action (such as a login) simply has nothing extra to display."
          ],
          faq: [
            {
              q: "What kinds of actions get recorded here?",
              a: "Logins, logouts, and record changes across the system."
            },
            {
              q: "What information does each log entry show?",
              a: "In the table: the action description, the user responsible, and the exact timestamp. Click the View (eye) icon to also see the full data snapshot that was stored with that entry."
            },
            {
              q: "What does the View button do?",
              a: "It opens the Log Details window for that row, showing the Date & Time, User, and Activity along with a readable field-by-field breakdown of the data that was recorded — or a note that the entry has no extra details."
            }
          ]
        },
        {
          id: "profile",
          title: "My Profile",
          route: "/system/profile",
          permission: "None — available to every signed-in user",
          screenshot: "profile.png",
          description:
            "Your own account page (titled “My Account”) — view your account details, change your photo, and change your password.",
          features: [
            "Account Information — your Full Name, Username, Email Address and Account Type (read-only).",
            "Change Photo — the pencil icon on your avatar opens a dialog to upload a new picture (PNG, JPEG or GIF).",
            "Security Settings — Current Password, New Password (with a strength meter) and Confirm New Password."
          ],
          steps: [
            "Click your avatar in the top-right corner of any page and choose My Profile.",
            "Review your account details.",
            "To change your photo, click the pencil icon on your avatar, choose an image, and click Update.",
            "To change your password, enter your Current Password, then your New Password twice, and click Update Password (Reset clears the fields).",
            "Use Log Out from the same avatar menu when you're done."
          ],
          tips: [
            "The new password must be at least 6 characters, and both new-password fields must match. Use the strength meter as a guide — longer passwords with a mix of characters score higher.",
            "Your full name, username and email can't be edited here; ask an administrator to change them under System Settings → User Accounts.",
            "Changing your password or photo is recorded in System Logs (“Update Own Password”, “Updated Profile Picture”)."
          ],
          faq: [
            {
              q: "How do I get to My Profile?",
              a: "Click your avatar in the top-right corner of any page and choose My Profile."
            },
            {
              q: "How do I change my own password?",
              a: "On My Profile, enter your current password and the new one twice under Security Settings, then click Update Password. It must be at least 6 characters."
            },
            {
              q: "Why was my password change refused?",
              a: "Either the current password was wrong, the new password is shorter than 6 characters, or the two new-password fields do not match."
            },
            {
              q: "Can I change my own name or email here?",
              a: "No — those are read-only on this page. An administrator can change them under System Settings → User Accounts."
            },
            {
              q: "How do I log out?",
              a: "Choose Log Out from the same avatar menu used to reach My Profile."
            }
          ]
        }
      ]
    }
  ]
};

// Flat lookup index built once at load time: "module-id/function-id" -> function object (+ moduleId/moduleTitle)
const MANUAL_INDEX = (() => {
  const idx = {};
  MANUAL_DATA.modules.forEach((mod) => {
    mod.functions.forEach((fn) => {
      idx[`${mod.id}/${fn.id}`] = { ...fn, moduleId: mod.id, moduleTitle: mod.title, moduleIcon: mod.icon };
    });
  });
  return idx;
})();
