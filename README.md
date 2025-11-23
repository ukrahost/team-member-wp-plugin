# Team Manager

A custom WordPress plugin to manage team members using a custom post type, taxonomy, meta fields, shortcode, and a custom REST API endpoint.

## Project Structure

- `team-manager.php`: Main plugin file, loads the plugin and bootstrap logic.
- `includes/class-team-manager-plugin.php`: Main singleton plugin class that wires all components and hooks.
- `includes/class-team-manager-cpt.php`: Registers the `team_member` custom post type.
- `includes/class-team-manager-taxonomy.php`: Registers the hierarchical `work_unit` taxonomy.
- `includes/class-team-manager-metaboxes.php`: Registers and handles meta boxes and custom fields (description, email, LinkedIn profile).
- `includes/class-team-manager-shortcode.php`: Defines the `[team_members_grid]` shortcode for frontend rendering.
- `includes/class-team-manager-rest-controller.php`: Registers the custom REST API endpoint to list team members.
- `assets/css/frontend.css`: Minimal CSS used by the frontend grid layout.

## Shortcode Usage

Shortcode: `\[team_members_grid\]`

Parameters:

- `unit` (optional): Work Unit slug to filter team members by taxonomy `work_unit`.
- `count` (optional): Maximum number of team members to display. Default is `9`.

Examples:

- `\[team_members_grid\]`  
  Displays up to 9 team members from any work unit.

## API Endpoint Usage

Endpoint:

- `GET /wp-json/v1/team-manager/members`

Query parameters:

- `unit` (optional): Work Unit slug to filter team members by taxonomy `work_unit`.  
  Example: `/wp-json/v1/team-manager/members?unit=marketing`
- `limit` (optional): Maximum number of results to return.  
  Example: `/wp-json/v1/team-manager/members?limit=5`

Response example:

```json
[
  {
    "id": 123,
    "title": "John Doe",
    "description": "Short bio here...",
    "email": "john.doe@example.com",
    "linkedin_url": "https://www.linkedin.com/in/johndoe",
    "work_unit": "marketing"
  }
]
```
