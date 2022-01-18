import {React, useState} from 'react'
import { __ } from '@wordpress/i18n';
import SVG from 'react-inlinesvg';
import Select from './Select.jsx'

const index = () => {

	const { countries, projects } = window.fundingScriptData;

	const [project, setProject] = useState('');
	const [country, setCountry] = useState('');
	const [amount, setAmount] = useState(25);
	const [paymentInfo, setPaymentInfo] = useState({ result: false });

	let qrCodeImage = "";

	const showPaymentInfo = () => {
		if( project == '') return;
		if( country == '') return;

		fetch(`/wp-admin/admin-ajax.php?action=funding_payment_info&project=${project}&country=${country}`).then((response) => response.json()).then((response) => {
			if(!response) {
				return;
			}
			setPaymentInfo(response);
			return;
        })	
	}

	return (
		<div className="grid grid--columns-2 grid--gap-12">
			<div>
			<form className="form">
				<div className="select">
					<label>{__("Your Country", "funding")}</label>
					<select onChange={(e) => {setCountry(e.target.value)}}>
							<option>{__("Select Country", "funding")}</option>
						{ countries.map(country => {
							return <option value={country.name}>{country.title}</option>
						}) }
					</select>
				</div>
				<Select 
					options={projects}
					label={__("Project", "funding")}
					value={project}
					setValue={setProject}
				/>
				<div className="input">
					<label>{__("Amount", "funding")}</label>
					<input value={amount} onChange={(event) => {setAmount(event.target.value)}} type="number"/>
				</div>
				<div className="button-group button-group--right">
					<a onClick={() => {showPaymentInfo()}} class={"button button--primary" + ((!amount || !country || !project) ? " button--disabled" : "")} href="#/">{__("Show Data", "funding")}</a>
				</div>
				<div className='mt-auto'></div>
			</form>
			</div>
			<div className="result">
				{
					paymentInfo.result && <div>
						<div className='card bg-white card--center card--no-image card--shadow'><h3 className='card__title'>{__("Scan to pay", "funding")}</h3><SVG className="w-full" src={`/wp-admin/admin-ajax.php?action=funding_qr_code&project=${project}&country=${country}`}></SVG></div>
						<table class="mt-12 table--dotted">
							<tr>
								<td>{__("IBAN", "funding")}</td>
								<td>{paymentInfo.iban}</td>
							</tr>
							<tr>
								<td>{__("Purpose", "funding")}</td>
								<td>{paymentInfo.purpose}</td>
							</tr>
							<tr>
								<td>{__("BIC", "funding")}</td>
								<td>{paymentInfo.bic}</td>
							</tr>
							<tr>
								<td>{__("Beneficiary", "funding")}</td>
								<td>{paymentInfo.beneficiary}</td>
							</tr>
							<tr>
								<td>{__("Bank", "funding")}</td>
								<td>{paymentInfo.bank}</td>
							</tr>
							<tr>
								<td>{__("Amount", "funding")}</td>
								<td>{amount}</td>
							</tr>
						</table>
					</div>
				}
				
					
			</div>
		</div>
	)
}

export default index
