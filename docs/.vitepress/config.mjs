import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Lathe",
  description: "Native PHP templating",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    // nav: [
    //   { text: 'Начало работы', link: '/index' },
    // ],
    base: '/lathe/',
    sidebar: [
      {
        // text: 'Examples',
        items: [
          { text: 'Начало работы', link: '/index' },
          { text: 'Использование', link: '/how-to' },
          { text: 'Справочник', link: '/reference' },
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/kolodochka-dev/lathe' }
    ]
  }
})
