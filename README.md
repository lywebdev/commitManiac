# CommitManiac

CommitManiac is a program that automatically publishes changes to a GitHub repository, making your activity statistics "green."

## How to use?
1. Create your own repository
2. Clone the contents of the current repository to yours
3. Configure environment variables in your repository: `Settings -> Secrets and variables -> Actions`, create the following variables:
- `APIKEY` - contains a token with the access rights to make changes to the repository (information about this can be found on third-party websites)
- `OWNER` - GitHub username (for example, for my profile https://github.com/lywebdev, the username will be lywebdev)
- `REPO` - repository slug (for example, for the current repository https://github.com/lywebdev/commitManiac, the slug will be commitManiac)

## Nuances
In this version of the program, a random quote is published, obtained from [this service](https://quoteslate.vercel.app).

**Feel free to thank by giving a star to this repository!**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-%230077B5.svg?logo=linkedin&logoColor=white)](https://www.linkedin.com/in/lkwebdev/)