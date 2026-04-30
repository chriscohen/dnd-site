import MarkdownIt from 'markdown-it';

const md = new MarkdownIt({
    html: false,
    breaks: true,
    linkify: true,
});

export const useMarkdown = () => {
    return {
        render: (content: string | undefined) => md.render(content || '')
    }
}
