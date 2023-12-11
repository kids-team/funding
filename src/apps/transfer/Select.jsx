/**
 * This module could be extracted as an NPM module later, as we may need it for other purposes.
 */

/**
 * External dependencies
 */
import { React, useRef, useState } from 'react';

/**
 * @augments {Component<{options: object, label: tring, value: string, setValue: function, placeholder: string}>}
 * @param props
 * @returns
 */
const Select = (props) => {
	const { options, label, value, setValue, placeholder } = props;

	const [showDropdown, setShowDropdown] = useState(false);
	const [inputTitle, setInputTitle] = useState('');
	const [filteredOptions, setFilteredOptions] = useState(options);

	const inputRef = useRef();

	const setNewValue = (option) => {
		setValue(option.name);
		setInputTitle(option.title);
		setShowDropdown(false);
		inputRef.current.blur();
	};

	const filterOptions = (value) => {
		setFilteredOptions((selection) =>
			options.filter((item) => {
				return item.title.toLowerCase().includes(value.toLowerCase());
			})
		);
		setInputTitle(value);
	};

	return (
		<div
			className={
				'input combobox ' + (showDropdown ? 'combobox--open' : '')
			}
		>
			<input type="hidden" value={value}></input>

			<div
				tabIndex={0}
				onFocus={() => {
					setShowDropdown(true);
				}}
				onBlur={() => {
					setShowDropdown(false);
				}}
			>
				<label>{label}</label>
				<input
					ref={inputRef}
					type="text"
					value={inputTitle}
					onClick={() => {
						setInputTitle('');
					}}
					onChange={(value) => {
						filterOptions(value.target.value);
					}}
				/>
				<ul className="options">
					{filteredOptions.map((option) => {
						return (
							<li
								onClick={() => {
									setNewValue(option);
								}}
							>
								{option.thumbnail && (
									<img src={option.thumbnail}></img>
								)}
								{!option.thumbnail && (
									<div className="combobox__dummy"></div>
								)}
								<div>
									{option.title}
									<br />
									<span>{option.description}</span>
								</div>
							</li>
						);
					})}
				</ul>
			</div>
		</div>
	);
};

export default Select;
