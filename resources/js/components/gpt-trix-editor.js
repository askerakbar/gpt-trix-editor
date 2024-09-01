export default function trixEditor({ state }) {
    return {
        state,
        init: function () {
            this.$refs.trixValue.value = this.state
            this.$refs.trix.editor?.loadHTML(this.state ?? '')

            this.$watch('state', () => {
                if (document.activeElement === this.$refs.trix) {
                    return
                }
                this.$refs.trixValue.value = this.state
                this.$refs.trix.editor?.loadHTML(this.state ?? '')
            })
        },
        updateSelectedContent: function(content = ""){
            if(!content.trim().length){
                return;
            }
            document.execCommand('insertHTML', true,  content);
            this.$refs.trix?.editor.recordUndoEntry(content);
        },
        updateContent: function(content = ''){
            if(!content.trim().length){
                return;
            }
            var activeElement = window.document.activeElement;
            var editor = this.$refs.trix?.editor;
            editor.recordUndoEntry(content);
            editor.setSelectedRange([0,editor.getDocument().getLength()]);
            editor.insertHTML(content);
            activeElement && activeElement.focus && activeElement.focus();
        }
    }
}
