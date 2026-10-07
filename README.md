<picture>
  <source media="(prefers-color-scheme: dark)" srcset="readme-assets/header-dark.svg">
  <source media="(prefers-color-scheme: light)" srcset="readme-assets/header-light.svg">
  <img alt="毕业设计问卷系统 · ✦ EricMingle69" src="readme-assets/header-light.svg" width="100%">
</picture>

<p align="center">
  <a href="README.md">简体中文</a> · <a href="README.en.md">English</a> · <a href="PERSONAL-NOTICE.md">✦ EricMingle69</a>
</p>

# 毕业设计问卷系统

## 项目定位

围绕行李箱用户体验与优化需求的 PHP 问卷源码，包含调研入口、三个问卷模块和管理页面。

## 阅读入口

| 入口 | 内容 |
| --- | --- |
| [调研入口](问卷网页/入口界面/index.php) | 欢迎与入口页面 |
| [问卷主界面](问卷网页/问卷模块/主页面/主界面.php) | 三个问卷的选择入口 |
| [问卷一](问卷网页/问卷模块/问卷1/) | 页面与提交处理 |
| [问卷二](问卷网页/问卷模块/问卷2/) | 页面与提交处理 |
| [问卷三](问卷网页/问卷模块/问卷3/) | 页面与提交处理 |
| [管理页面](问卷网页/问卷模块/管理页面/index.php) | 调研管理入口 |
| [共享资源](问卷网页/共用资源/) | CSS、JavaScript 与 PHP 文件 |

## 从哪里开始

1. 在仓库 Code 页面阅读或下载源码，先看调研入口与问卷主界面。
2. 运行前确认 PHP/Web 服务、数据库类型与初始化方式，以及管理账号流程。
3. 入口依赖会话和数据库连接，直接打开 PHP 文件不能完成调研流程。

## 使用边界

- 当前没有完整部署指南、SQL 建库脚本或经验证的环境版本。
- 问卷设计依据、管理账号初始化与答卷处理政策仍需补充。
- 实际调研前应明确受访者告知和数据管理方式；示例与反馈使用合成数据。

## 来源与原有许可

仓库归属不能单独证明所有代码与素材原创。原文件树没有 LICENSE/NOTICE，来源与复制、修改、再分发权限仍需明确。

---

文档维护：**✦ EricMingle69** · [Ming-Sir-69](https://github.com/Ming-Sir-69)  
[个人标识、许可与权限说明](PERSONAL-NOTICE.md) · 明暗页眉随 GitHub 主题自动切换。
