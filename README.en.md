<picture>
  <source media="(prefers-color-scheme: dark)" srcset="readme-assets/header-dark.svg">
  <source media="(prefers-color-scheme: light)" srcset="readme-assets/header-light.svg">
  <img alt="Graduation Project Questionnaire System · ✦ EricMingle69" src="readme-assets/header-light.svg" width="100%">
</picture>

<p align="center">
  <a href="README.md">简体中文</a> · <a href="README.en.md">English</a> · <a href="PERSONAL-NOTICE.md">✦ EricMingle69</a>
</p>

# Graduation Project Questionnaire System

## Purpose

PHP questionnaire source for research into luggage user experience and optimization needs, including a survey entry, three questionnaire modules and administration pages.

## Repository guide

| Entry | Contents |
| --- | --- |
| [Survey entry](问卷网页/入口界面/index.php) | Welcome and entry page |
| [Questionnaire menu](问卷网页/问卷模块/主页面/主界面.php) | Selection entry for the three questionnaires |
| [Questionnaire 1](问卷网页/问卷模块/问卷1/) | Page and submission handling |
| [Questionnaire 2](问卷网页/问卷模块/问卷2/) | Page and submission handling |
| [Questionnaire 3](问卷网页/问卷模块/问卷3/) | Page and submission handling |
| [Administration page](问卷网页/问卷模块/管理页面/index.php) | Survey administration entry |
| [Shared resources](问卷网页/共用资源/) | CSS, JavaScript and PHP files |

## Getting started

1. Browse or download the source from the repository Code page, starting with the survey entry and questionnaire menu.
2. Before running it, establish the PHP/web server environment, database type and initialization, and administration account flow.
3. The entry depends on sessions and a database connection; opening PHP files directly cannot complete the survey flow.

## Scope and limitations

- A complete deployment guide, SQL initialization scripts and validated environment versions are not provided.
- Questionnaire design rationale, administrator initialization and response-handling policy still need documentation.
- Before a real survey, establish participant information and data handling; use synthetic data in examples and reports.

## Sources and existing licenses

Repository ownership alone does not establish original authorship of all code and assets. The original tree has no LICENSE/NOTICE; provenance and rights to copy, modify and redistribute remain to be clarified.

---

Documentation maintained by **✦ EricMingle69** · [Ming-Sir-69](https://github.com/Ming-Sir-69)  
[Personal identity, licensing and permissions](PERSONAL-NOTICE.md) · The header follows your GitHub theme.
