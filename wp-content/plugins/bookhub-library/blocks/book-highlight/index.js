import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';

import './style.css';

registerBlockType('bookhub/book-highlight', {
    title: 'Book Highlight',
    icon: 'book',
    category: 'widgets',
    attributes: {
        selectedBook: {
            type: 'number',
        },
    },
    edit: (props) => {
        const blockProps = useBlockProps();
        const books = useSelect((select) =>
            select('core').getEntityRecords('postType', 'book', { per_page: -1 })
        );

        return (
            <div {...blockProps}>
                <SelectControl
                    label="Select Book"
                    value={props.attributes.selectedBook}
                    options={
                        books
                            ? books.map((book) => ({ label: book.title.rendered, value: book.id }))
                            : [{ label: 'Loading...', value: 0 }]
                    }
                    onChange={(value) => props.setAttributes({ selectedBook: parseInt(value) })}
                />
            </div>
        );
    },
    save: () => {
        return null; // dynamic block handled by PHP
    },
});
