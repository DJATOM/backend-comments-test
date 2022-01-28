<template>
    <div
        v-for="comment in comments"
        :key="comment.id"
        class="comment-container"
    >
        <div
            class="comment-body"
        >
            <div
                v-if="inEditing(comment.id)"
                class="comment-edit"
            >
                <textarea :id="'edit-' + comment.id">{{ comment.content }}</textarea>
                <div class="comment-actions">
                    <a @click.stop="saveComment(comment.id)">Сохранить</a>
                    <a @click.stop="discardComment">Отмена</a>
                </div>
            </div>
            <template v-else>
                {{ comment.content }}
            </template>
        </div>
        <div
            v-if="!inEditing(comment.id)"
            class="comment-actions"
        >
            <a @click.stop="editComment(comment.id)">Редактировать</a>
            <a @click.stop="deleteComment(comment.id)">Удалить</a>
            <a @click.stop="addReply(comment.id)">Ответить</a>
        </div>
        <div
            v-if="inReplying(comment.id)"
            class="comment-reply"
        >
            <textarea :id="'reply-' + comment.id"></textarea>
            <div class="comment-actions">
                <a @click.stop="saveReply(comment.id)">Добавить ответ</a>
                <a @click.stop="discardReply">Отмена</a>
            </div>
        </div>
        <template
            v-if="comment.replies.length > 0"
        >
            <comments-list
                :comments="comment.replies"
                @reload="$emit('reload')"
            />
        </template>
    </div>
</template>

<script>
export default {
    name    : 'CommentsList',
    props   : {
        comments: {
            type   : Array,
            default: null,
        },
    },
    emits: ["reload"],
    methods: {
        editComment (id) {
           this.$store.commit('setEditing', id)
           this.$store.commit('setReplying', null)
        },
        saveComment (id) {
            const data = {content: document.querySelector(`#edit-${id}`).value}
            axios.patch(`/api/comments/${id}`, data)
                .then(() => {
                    this.$swal.fire('Комментарий изменен!', '', 'success')
                    this.$store.commit('setEditing', null)
                    this.$emit('reload')
                })
                .catch((error) => {
                    this.$swal(Object.values(error.response.data.errors).join('<br>'))
                })
        },
        deleteComment (id) {
            this.$swal.fire({
                title: 'Удалить комментарий?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Да',
                denyButtonText: `Нет`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/comments/${id}`)
                        .then(() => {
                            this.$swal.fire('Готово!', '', 'success')
                            this.$emit('reload')
                        })
                        .catch((error) => {
                            this.$swal(Object.values(error.response.data.errors).join('<br>'))
                        })
                }
            })
        },
        addReply (id) {
           this.$store.commit('setEditing', null)
           this.$store.commit('setReplying', id)
        },
        saveReply (id) {
            const data = {
                content:    document.querySelector(`#reply-${id}`).value,
                comment_id: id
            }
            axios.post(`/api/comments`, data)
                .then(() => {
                    this.$swal.fire('Ответ добавлен!', '', 'success')
                    this.$store.commit('setReplying', null)
                    this.$emit('reload')
                })
                .catch((error) => {
                    this.$swal(Object.values(error.response.data.errors).join('<br>'))
                })
        },
        discardComment () {
           this.$store.commit('setEditing', null)
        },
        discardReply () {
           this.$store.commit('setReplying', null)
        },
        inEditing (id) {
            return this.$store.state.editing === id
        },
        inReplying (id) {
            return this.$store.state.replying === id
        },
    }
}
</script>