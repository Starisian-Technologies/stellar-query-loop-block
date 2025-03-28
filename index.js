import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import {
    PanelBody,
    SelectControl,
    TextControl
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';

registerBlockType('stellar/query-loop-block', {
    title: 'User Post Query',
    icon: 'admin-users',
    category: 'widgets',
    attributes: {
        postType: { type: 'string', default: 'post' },
        postStatus: { type: 'array', default: ['publish'] },
        authorType: { type: 'string', default: 'all' },
        specificAuthor: { type: 'string', default: '' },
        taxonomy: { type: 'string', default: '' },
        taxonomyTerm: { type: 'string', default: '' },
        postsPerPage: { type: 'string', default: '10' },
        order: { type: 'string', default: 'DESC' },
        orderBy: { type: 'string', default: 'date' }
    },
    edit({ attributes, setAttributes }) {
        const blockProps = useBlockProps();
        const postTypes = useSelect((select) => select('core').getPostTypes({ per_page: -1 }) || [], []);
        const taxonomies = useSelect((select) => select('core').getTaxonomies() || [], []);

        const [statusOptions, setStatusOptions] = useState([]);
        useEffect(() => {
            if (window.StellarQueryLoopBlockPostStatusOptions) {
                setStatusOptions(window.StellarQueryLoopBlockPostStatusOptions);
            }
        }, []);

        return (
            <>
                <InspectorControls>
                    <PanelBody title="Query Settings">
                        <SelectControl
                            label="Post Type"
                            value={attributes.postType}
                            options={postTypes.map((type) => ({ label: type.name, value: type.slug }))}
                            onChange={(val) => setAttributes({ postType: val })}
                        />
                        {statusOptions.length > 0 && (
                            <>
                                <label>Post Status</label>
                                {statusOptions.map((status) => (
                                    <div key={status.value}>
                                        <input
                                            type="checkbox"
                                            checked={attributes.postStatus.includes(status.value)}
                                            onChange={(e) => {
                                                const newStatus = [...attributes.postStatus];
                                                if (e.target.checked) {
                                                    newStatus.push(status.value);
                                                } else {
                                                    const index = newStatus.indexOf(status.value);
                                                    if (index > -1) newStatus.splice(index, 1);
                                                }
                                                setAttributes({ postStatus: newStatus });
                                            }}
                                        />
                                        {status.label}
                                    </div>
                                ))}
                            </>
                        )}
                        <SelectControl
                            label="Author Filter"
                            value={attributes.authorType}
                            options={[
                                { label: 'All Authors', value: 'all' },
                                { label: 'Current User', value: 'current' },
                                { label: 'Specific Author ID', value: 'specific' }
                            ]}
                            onChange={(val) => setAttributes({ authorType: val })}
                        />
                        {attributes.authorType === 'specific' && (
                            <TextControl
                                label="Author ID"
                                value={attributes.specificAuthor}
                                onChange={(val) => setAttributes({ specificAuthor: val })}
                            />
                        )}
                        <SelectControl
                            label="Taxonomy"
                            value={attributes.taxonomy}
                            options={[{ label: 'None', value: '' }].concat(
                                taxonomies.map((t) => ({ label: t.name, value: t.slug }))
                            )}
                            onChange={(val) => setAttributes({ taxonomy: val })}
                        />
                        {attributes.taxonomy && (
                            <TextControl
                                label="Taxonomy Term Slug"
                                value={attributes.taxonomyTerm}
                                onChange={(val) => setAttributes({ taxonomyTerm: val })}
                            />
                        )}
                        <TextControl
                            label="Posts Per Page"
                            value={attributes.postsPerPage}
                            onChange={(val) => setAttributes({ postsPerPage: val })}
                        />
                        <SelectControl
                            label="Order By"
                            value={attributes.orderBy}
                            options={[
                                { label: 'Date', value: 'date' },
                                { label: 'Title', value: 'title' },
                                { label: 'Modified', value: 'modified' }
                            ]}
                            onChange={(val) => setAttributes({ orderBy: val })}
                        />
                        <SelectControl
                            label="Order"
                            value={attributes.order}
                            options={[
                                { label: 'Descending', value: 'DESC' },
                                { label: 'Ascending', value: 'ASC' }
                            ]}
                            onChange={(val) => setAttributes({ order: val })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    <ServerSideRender block="stellar/query-loop-block" attributes={attributes} />
                </div>
            </>
        );
    },
    save() {
        return <InnerBlocks.Content />;
    }
});
