# Mr Mikes WP Theme

[![Website](https://img.shields.io/website?url=https%3A%2F%2Fmrmikes.ca%2F&label=Mr%20Mikes%20Production&up_message=online)](https://mrmikes.ca/)
[![Website](https://img.shields.io/website?url=https://mrmikeslive-staging.gvngsevj6t-xlm41dv224dy.p.temp-site.link&label=Mr%20Mikes%20Staging&down_message=inaccessible&up_message=online)](https://mrmikeslive-staging.gvngsevj6t-xlm41dv224dy.p.temp-site.link)

1. **[Introduction](#introduction)**

   - Welcome! 🙏 Please Read about the key aspects of the Mr Mikes WP Theme project.

2. **[Deployment Process](#deployment-process)**

   - [2.1 Production Deployment](#production-deployment): Description of the deployment process for the production environment, including the live site's URL.
   - [2.2 Staging Deployment](#staging-deployment): Details of the deployment process for the staging environment, including the staging site's URL.
   - [2.3 Automated Comments on Merge](#automated-comments-on-merge): Information about the automated comments posted upon successful merging to the main or staging branches.
   - [2.4 Technical Details](#technical-details): Explanation of the technical aspects of the deployment process, including the use of SFTP and repository settings.
   - [2.5 Best Practices](#best-practices): Best practices for using the theme, including testing, database syncing, and commit protocols.

3. **[Important Note to Developers Working on This Legacy Project](#important-note-to-developers-working-on-this-legacy-project)**
   - [3.1 Background and Current State](#background-and-current-state): Description of the project's origin, evolution, and current state.
   - [3.2 Future Considerations and Recommendations](#future-considerations-and-recommendations): Recommendations for future development, including:
     - [3.2.1 Streamlining CSS Management](#streamlining-css-management): Suggestions for improving CSS management.
     - [3.2.2 Enhancing JavaScript Workflow](#enhancing-javascript-workflow): Recommendations for modernizing the JavaScript workflow.
     - [3.2.3 Moving Forward](#moving-forward): Encouragement for developers to adhere to structured and sustainable workflows.

## Introduction

This repository includes GitHub Actions workflows to automate the deployment of the WordPress theme to both production and staging environments. The deployment process is triggered by merges or direct pushes to the main and staging branches.

## Deployment Process

### Production Deployment:

Any updates made to the main branch will automatically be deployed to the live site's theme. This ensures that changes are swiftly reflected on the production environment. The production environment is accessible at https://mrmikes.ca/.

### Staging Deployment:

Updates to the staging branch are deployed to the staging environment, providing a platform for testing and validation before going live. The staging environment can be accessed at Staging Environment.

### Automated Comments on Merge

Upon the successful merging of a pull request into either the main or staging branches, an automated comment is posted on the pull request. This comment confirms the deployment and includes a link to the front end of the respective environment, ensuring clear communication and visibility of the deployment process.

### Technical Details

The deployment process involves a git checkout of the respective branch (main or staging) and subsequently uploads only the changed theme files over SFTP to the remote directory where the theme is located.

All necessary configurations such as SFTP credentials and remote directory paths are securely stored as secrets and variables in this repository's [settings](https://github.com/Jambaree/mr-mikes-wp/settings/secrets/actions).

### Best Practices

**Testing on Staging**: It is highly recommended to first open pull requests against the staging branch. This allows for thorough testing and review in a controlled environment. After verification on staging, changes can be confidently merged into main.

**Database Syncing**: The database can be easily synced from the production to the staging environment using Runcloud. For assistance with this process, feel free to tag relevant team members.

**Direct Commits to Main**: Direct commits to the main branch are discouraged. Although this branch is not protected (due to the current plan limitations), it is crucial to maintain a structured and review-driven workflow to ensure stability in the production environment.

---

This workflow streamlines the process of deploying and managing the WordPress theme, ensuring a reliable and efficient development lifecycle. Please adhere to the outlined practices for a smooth and consistent deployment experience.

---

# Important Note to Developers Working on This Legacy Project

## Background and Current State

This WordPress theme originated from an adaptation of the Bare Naked Theme and initially included a build process. However, over time and without strict adherence to a structured workflow, the development approach shifted. Developers began directly modifying the generated "build" theme files, rather than working on the source code.

This change in practice led to the accumulation of styles in a single styles.css file, bypassing the original build process. The original source, which utilized an older version of Node.js and gulp for bundling Sass files, was thus sidelined.

## Future Considerations and Recommendations

### Streamlining CSS Management

It is advisable to address this deviation from best practices in future development cycles. A recommended approach would be to integrate a more contemporary CSS processing step, such as PostCSS. This integration would not only modernize the development process but also facilitate better organization and maintainability of the stylesheets.

**The proposed restructuring would involve**:

- Integrating the existing base styles.css into a modern CSS processing system, perhaps naming this /styles/base.css or similar.
- Organizing new styles into separate, well-structured files, enhancing readability and scalability.
- Output the combined styles into the main styles.css file.
- Keep in mind, WP themes pull the theme name/version from a comment at the top of this styles.css file.

### Enhancing JavaScript Workflow

In addition to CSS restructuring, we recommend modernizing the JavaScript bundling process. Introducing a tool like esbuild can streamline and speed up JavaScript compilation. Esbuild stands out for its performance and simplicity, offering a more efficient way to manage and bundle JavaScript files. This change aims to improve maintainability, scalability, and front-end performance, aligning our JavaScript practices with current web development standards.

### Moving Forward

As developers contributing to this project, it is crucial to recognize the importance of maintaining a structured and sustainable workflow. By adopting modern development practices and tools, we can ensure the theme's longevity, scalability, and ease of maintenance.

The goal is to gradually transition from the current state to a more organized and efficient workflow, ensuring the theme evolves alongside modern web development standards. Your cooperation and adherence to these guidelines will be instrumental in achieving this transition.
